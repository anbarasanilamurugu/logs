<?php

class Log {
        
        private $log = "";

		private $lineCount;

		private $recordsToShow = 10;

        public function __construct($log, $maxSizeToLoad = 2097152) {
                $this->log = $log;
				$this->lineCount = 0;
        }
		
		public function getLineCount() {
			$linecount = 0;
			$handle = fopen($this->log, "r");
			while(!feof($handle)){
  			    $line = fgets($handle);
			    $linecount++;
			}
			$this->lineCount = $linecount;		
		}

        public function getNewLines($pageNum) {
				if ($this->lineCount == 0)
					$this->getLineCount();
                /**
                 * Clear the stat cache to get the latest results
                 */
                clearstatcache();

                /**
                 * load the data
                 */
                $data = array();
                $fp = new SplFileObject($this->log, "r");
                $fp->seek($pageNum * $this->recordsToShow);
				$count = 0;
				while (($line = $fp->fgets()) !== false) {
					// process the line read.
					$count++;
					$data[] = $line;
					if ($count >= $this->recordsToShow) {
						break;
					}
				}
				
                return json_encode(array("data" => $data, "paginate" => $this->getPaginationString($pageNum, $this->lineCount-5, $this->recordsToShow, 1,  $_SERVER['PHP_SELF'])));
        }


		//function to return the pagination string
		function getPaginationString($page = 1, $totalitems, $limit = 10, $adjacents = 1, $targetpage = "/", $pagestring = "?pagenum=")
		{		
			//defaults
			if(!$adjacents) $adjacents = 1;
			if(!$limit) $limit = 10;
			if(!$page) $page = 1;
			if(!$targetpage) $targetpage = "/";
			
			//other vars
			$prev = $page - 1;									//previous page is page - 1
			$next = $page + 1;									//next page is page + 1
			$lastpage = ceil($totalitems / $limit);				//lastpage is = total items / items per page, rounded up.
			$lpm1 = $lastpage - 1;								//last page minus 1
			
			/* 
				Now we apply our rules and draw the pagination object. 
				We're actually saving the code to a variable in case we want to draw it more than once.
			*/
			$pagination = "";
			if($lastpage > 1)
			{	
				$pagination .= "<div class=\"pagination\" >";

				//previous button
				if ($page > 1) 
					$pagination .= "<a href=\"$targetpage$pagestring$prev\" data-page=$prev>« prev</a>";
				else
					$pagination .= "<span class=\"disabled\">« prev</span>";	
				
				//pages	
				if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
				{	
					for ($counter = 1; $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination .= "<span class=\"current\">$counter</span>";
						else
							$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
					}
				}
				elseif($lastpage >= 7 + ($adjacents * 2))	//enough pages to hide some
				{
					//close to beginning; only hide later pages
					if($page < 1 + ($adjacents * 3))		
					{
						for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
						{
							if ($counter == $page)
								$pagination .= "<span class=\"current\">$counter</span>";
							else
								$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\" data-page=$counter>$counter</a>";					
						}
						$pagination .= "<span class=\"elipses\">...</span>";
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\" data-page=$lpm1>$lpm1</a>";
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\" data-page=$lastpage>$lastpage</a>";		
					}
					//in middle; hide some front and some back
					elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
					{
						$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\" data-page=1>1</a>";
						$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\" data-page=2>2</a>";
						$pagination .= "<span class=\"elipses\">...</span>";
						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
						{
							if ($counter == $page)
								$pagination .= "<span class=\"current\">$counter</span>";
							else
								$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\" data-page=$counter>$counter</a>";					
						}
						$pagination .= "...";
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\" data-page=$lpm1>$lpm1</a>";
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\" data-page=$lastpage>$lastpage</a>";		
					}
					//close to end; only hide early pages
					else
					{
						$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\" data-page=1>1</a>";
						$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\" data-page=2>2</a>";
						$pagination .= "<span class=\"elipses\">...</span>";
						for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++)
						{
							if ($counter == $page)
								$pagination .= "<span class=\"current\">$counter</span>";
							else
								$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\" data-page=$counter>$counter</a>";					
						}
					}
				}
				
				//next button
				if ($page < $counter - 1) 
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $next . "\" data-page=$next>next »</a>";
				else
					$pagination .= "<span class=\"disabled\">next »</span>";
				$pagination .= "</div>\n";
			}
			
			return $pagination;

		}
        /**
         * This function will print out the required HTML/CSS/JS
         */
        public function generateGUI() {
                ?>
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                                <title>PHPTail</title> 
                                <meta http-equiv="content-type" content="text/html;charset=utf-8" />

                                <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/flick/jquery-ui.css" rel="stylesheet"></link>
								<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
                                <style type="text/css">
                                        .results {
                                                padding-bottom: 20px;
                                        }
										.ui-widget-content a {
											color: blue;
											text-decoration: none;
											padding: 0 6px;
										}
										.data {
											border: 1px solid;
											margin: 10px 0;
											padding: 10px;
										}
										.current, .disabled {
											padding: 0 6px;
										}
                                </style>


								<script src="//code.jquery.com/jquery-1.10.2.js"></script>
    							<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>								
                                
                                <script type="text/javascript">
                                        /* <![CDATA[ */
                                        pagenum = 1;
                                        $(document).ready(function(){
											$( "#tabs" ).tabs();
											updateLog();
											$('#paging').on('click', 'a', function(e){
												e.preventDefault();
												pagenum = $(this).data('page');
												updateLog();
											});
                                        });
                                        //This function queries the server for updates.
                                        function updateLog() {
                                                $.getJSON('Log.php?ajax=1&pagenum='+pagenum , function(data) {
														$("#content").empty();
                                                        $.each(data.data, function(key, value) { 
                                                                $("#content").append('<div class="data">' + value + '</div>');
                                                        });
														$("#paging").empty().append(data.paginate);
                                                });
                                        }
                                        /* ]]> */
                                </script>
                        </head> 
                        <body>
								<div id="tabs">
								  <ul>
									<li><a href="#tabs-1">System Information</a></li>
									<li><a href="#tabs-2">Apache Logs</a></li>
									<li><a href="#tabs-3">Kernel Logs</a></li>
								  </ul>
								  <div id="tabs-1">
									<?php echo php_uname(); ?>
								  </div>
								  <div id="tabs-2">
									<div id="paging"></div>
									<div id="content"></div>
								  </div>
								  <div id="tabs-3">
								  In Progress ...
								  </div>
								</div>						
                                <div id="results">
                                </div>
                        </body> 
                </html> 
                <?php
        }
}