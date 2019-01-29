<?php
	/**
	 * admin/questions.php
	 *
	 * Lists the questions added to an exam
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

<center><h4 class="light-font">Questions</h4></center>
<br/>

<a href="new_question.php" class="btn waves-effect waves-light">New Question</a> 
<a href="import.php" class="btn waves-effect waves-light">Import Qustions</a><br/>

<br/><br/>

<table>
    <tr>
        <th>#</th>
        <th>Question</th>
        <th style="min-width:70px">Option 1</th>
        <th style="min-width:70px">Option 2</th>
        <th style="min-width:70px">Option 3</th>
        <th style="min-width:70px">Option 4</th>
        <th style="width:90px">Manage</th>
    </tr>
    <?php
        $questions = $conn->query("SELECT * FROM `question_pool` WHERE `exam`='$settings[active_exam]'");
        $i=1;
        while($row=$questions->fetch_array()):
    ?>
        <tr>
            <td><?php echo $i ?></td>
            <td><?php echo nl2br(htmlspecialchars($row["question"])) ?></td>
            <?php for($j=1;$j<=4;$j++): ?>
                <td <?php echo $j==$row['right_opt']?"style='background:#228B22;color:white;text-decoration:bold;'":"" ?>><?php echo htmlspecialchars($row["opt_".$j]) ?></td>
            <?php endfor; ?>
            <td>
                <a href="edit_question.php?q=<?php echo $row["id"] ?>&act=edit">Edit</a> 
                <a href="edit_question.php?q=<?php echo $row["id"] ?>&act=del">Delete</a>
            </td>
        </tr>
    <?php
        $i++;
        endwhile;
    ?>
</table>

<?php include "footer.php"; ?>