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