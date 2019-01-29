<?php
	/**
	 * admin/exams.php
	 *
	 * Lists the available exams
	 *
	 * @author     Ajith Gopi
	 * @copyright  2019, Ajith Gopi
	 * @license    https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE  BSD 3-Clause License
	 *
	 * DO NOT REMOVE THIS COPYRIGHT INFORMATION WITHOUT PERMISSION. YOU WILL BE VIOLATING THE LICENSE
	 * AGGREMENT WHEN YOU DO SO. (according to https://github.com/ajithgopi/mcq-quiz/blob/master/LICENSE).
	 */
	 
    require_once "../includes/connection.php";
	require_once "admin_login.php";
?>
<?php include "header.php"; ?>

<center><h4 class="light-font">Exams</h4></center>
<br/>

<br/>
<a href="new_exam.php" class="btn waves-effect waves-light">New</a><br/><br/>


<table>
    <tr>
        <th>#</th>
        <th>Name</th>
		<th>Alloted time</th>
		<th>Start at</th>
		<th>No. of questions</th>
		<th>Secured</th>
        <th>Manage</th>
    </tr>
    <?php
        $qsets = $conn->query("SELECT * FROM `exams`");
		$i=1;
        while($row=$qsets->fetch_array()):
    ?>
        <tr>
            <td><?php echo $i ?></td>
            <td><?php echo htmlspecialchars($row["name"]) ?></td>
            <td><?php echo $row["time_alloted"] ?> minute<?php echo $row["time_alloted"]>1?'s':'' ?></td>
            <td><?php echo date('d/m/Y, h:i A',strtotime($row["start_date"])) ?></td>
            <td><?php echo $row["nquestions"] ?></td>
            <td><?php echo $row["secured"]?'Yes':'No' ?></td>
            <td>
                <a href="edit_exam.php?id=<?php echo $row["id"] ?>&act=edit">Edit</a> 
                <a href="edit_exam.php?id=<?php echo $row["id"] ?>&act=del">Delete</a>
            </td>
        </tr>
    <?php
		$i++;
		endwhile;
	?>
</table>
<br/><br/>
<script>
$(document).ready(function(){
	$('select').formSelect();
});
</script>
<?php include "footer.php"; ?>