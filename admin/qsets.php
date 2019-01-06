<?php
    require_once "../includes/connection.php";
	require_once "admin_login.php";
?>
<?php include "header.php"; ?>
<center><h4 class="light-font">Question Sets</h4></center>
<br/>

<a href="question_set.php" class="waves-effect waves-light btn">New</a>
<table>
    <tr>
        <th>#</th>
        <th>Questions</th>
        <th>Manage</th>
    </tr>
    <?php
        $qsets = $conn->query("SELECT `qcode` FROM `question_sets` WHERE `exam`='$settings[active_exam]' GROUP BY `qcode`");
        $i=1;
		while($row=$qsets->fetch_array()):
    ?>
        <tr>
            <td>QS<?php echo $row['qcode'] ?></td>
            <td><?php echo $cexam["nquestions"] ?></td>
            <td>
                <a href="preview.php?qs=<?php echo $row["qcode"] ?>" target="_blank">Preview</a> 
                <a href="question_set.php?set=<?php echo $row["qcode"] ?>&act=edit">Edit</a> 
                <a href="question_set.php?set=<?php echo $row["qcode"] ?>&act=del">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include "footer.php"; ?>