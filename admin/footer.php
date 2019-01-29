<?php
	/**
	 * admin/footer.php
	 *
	 * Footer for admin side pages
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 *
	 *
	 * NO MODIFICATIONS ARE ALLOWED ON THIS FILE. ANY CHANGES MADE TO THIS FILE WOULD VIOLATE THE LICENSE
	 * AGGREMENT, AND LEGAL ACTIONS WILL BE TAKEN WITHOUT ANY CONSIDERATION OF THE LEVEL OF MODIFICATION
	 * DONE IN THIS SPECIFIC FILE. AND ALSO, ALL THE CONTENT IN THIS FILE SHOULD BE VISIBLE TO ALL THE USERS
	 * ON ALL PAGES OF THE SOFTWARE, EXACTLY AS HOW IT IS DISPLAYED NOW. AND YES, YOU CAN'T ADD YOUR NAME HERE
	 * BECAUSE YOU DIDN'T WRITE THIS SOFTWARE. IT'S THAT SIMPLE... IF YOU WANT IT TO BE ADDED, WRITE YOUR OWN
	 * SOFTWARE :)
	 *
	 */
?>
								<br/><br/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<center><span id="copyright">Copyright &copy; 2018. All rights reserved by Distro Studios.</span></center>
		<br/>
		<?php
			if(isset($_GET['msg'])){
				$msg = base64_decode($_GET['msg']);
			}
			if(isset($msg))
				echo "<script>M.toast({html: '".$conn->real_escape_string($msg)."'})</script>";
		?>
    </body>
  </html>