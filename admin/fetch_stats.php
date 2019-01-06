<?php
    require_once "../includes/connection.php";
	function decodeStatus($status){
		switch($status){
			case 0:
				return "<span style='color:#F99911'>Ongoing</span>";
			case 1:
				return "<span style='color:#04A777'>Completed</span>";
		}	
	}
	
	updateRegStatus();
	
	$last_reg = $conn->query("SELECT * FROM `registrations` WHERE `exam`='$settings[active_exam]' AND `status`='0' ORDER BY `date_registered` DESC LIMIT 1")->fetch_array();
	
	$regs = $conn->query("SELECT *,
							(SELECT SUM(`score`) FROM `answers` WHERE `regid`=`registrations`.`id`) AS `scr`,
							(SELECT COUNT(*) FROM `answers` WHERE `regid`=`registrations`.`id`) AS `ansd`
						FROM `registrations` WHERE `exam`='$settings[active_exam]' 
						ORDER BY ((`scr`*'$cexam[pos_mark]') - ((`ansd`-`scr`)*'$cexam[neg_mark]')) DESC,(`date_completed`-`date_registered`)");
?>
<center><h4 class="light-font">Live Stats (<?php echo $regs->num_rows ?> Participants, <?php echo $cexam['nquestions']*$cexam['pos_mark'] ?> Total score)</h4></center>
<br/>
<?php if($regs->num_rows>0): ?>
	<center><span id="ends_in"></span><br/><br/><center>
	<table>
		<tr>
			<th>Rank</th>
			<th>Reg. code</th>
			<th>Participants</th>
			<th>Institute</th>
			<th>Score</th>
			<th>Status</th>
		</tr>
		<?php
			$rank=0;
			$last_score='';
			while($row=$regs->fetch_array()):
				$right=intval($row["scr"]);
				$negative = ($row['ansd']-$right)*$cexam['neg_mark'];
				$positive = $right*$cexam['pos_mark'];
				$score = $positive-$negative;
				if($last_score!=$score)
					$rank++;
				
		?>
		<tr>
			<td><?php echo $rank ?></td>
			<td><a title="View answers" target="_blank" href="answers.php?reg=<?php echo $row["id"] ?>"><?php echo $row["regcode"] ?></a></td>
			<td><?php echo $row["participants"] ?></td>
			<td><?php echo $row["institute"] ?></td>
			<td><span title="Score"><b><?php echo $score; ?></span></b><sup><span title="Marks without negative"><?php echo $positive; ?></span><span title="Negative marks deducted">-<?php echo $negative; ?></span></sup>/(<span title="Right answers"><?php echo $right; ?></span>/<span title="Answered"><?php echo $row['ansd']; ?></span>/<span title="Total questions"><?php echo $cexam['nquestions']; ?></span>)</td>
			<td><b><span
				<?php if($row["status"]==-1): ?>
					onclick="unban(<?php echo $row['id'] ?>)" class="ban-btn"
				<?php endif; ?>
				
				><?php echo decodeStatus($row["status"]); ?></span></b>
			</td>
		</tr>
		<?php
			$last_score = $score;
			endwhile;
		?>
	</table>
	<style>
		.ban-btn{
			width: 80px;
			display: inline-block;
		}
		.ban-btn:before{
			content:'Banned';
			color:#D80044;
		}
		.ban-btn:hover:before{
			content:'Unban';
			color:#44CF6C;
		}
	</style>
	<script>
		var ends_in = <?php echo (strtotime($last_reg['date_registered'])+($cexam['time_alloted']*60))-time();?>;
		
		updateEndTime();
		function updateEndTime(){
			if(ends_in>=0)
				$("#ends_in").html("The last exam ends in "+secondsToHms(ends_in));
			else
				$("#ends_in").html("All the exams have completed!");
		}
		
		function unban(prtcid){
			$.post("unban.php",{
				pid:prtcid
			},function(data,status){
				M.toast({html: data=="OK"?'Partcipant unbanned':'Something went wrong'});
				fetch_stats();
			});
		}
		function secondsToHms(d){
			d = Number(d);
			var h = Math.floor(d / 3600);
			var m = Math.floor(d % 3600 / 60);
			var s = Math.floor(d % 3600 % 60);
			return (h>0?(h+"h:"):"") + (m>0?(pad(m,2)+"m:"):"") + pad(s,2)+"s";
		}
		function pad(num, size) {
			var s = num+"";
			while (s.length < size) s = "0" + s;
			return s;
		}
	</script>
<?php endif; ?>