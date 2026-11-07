<?php
require_once 'includes.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $action=post('action');
  try{
    if($action==='save'){
      $id=(int)post('id','0'); $data=[post('student_name'),post('email'),post('phone'),post('gender'),post('course')];
      if(!$data[0]||!$data[1]||!$data[4]) throw new Exception('Name, email and course are required.');
      if($id){$data[]=$id;$q=$pdo->prepare("UPDATE students SET student_name=?,email=?,phone=?,gender=?,course=? WHERE student_id=?");$q->execute($data);flash('Student record updated.');}
      else{$q=$pdo->prepare("INSERT INTO students(student_name,email,phone,gender,course) VALUES(?,?,?,?,?)");$q->execute($data);flash('Student added successfully.');}
    }elseif($action==='delete'){
      $q=$pdo->prepare("DELETE FROM students WHERE student_id=?");$q->execute([(int)post('id')]);flash('Student deleted.');
    }
  }catch(Throwable $e){flash($e->getMessage(),'error');}
  redirect('students.php');
}
$edit=null;if(isset($_GET['edit'])){$q=$pdo->prepare("SELECT * FROM students WHERE student_id=?");$q->execute([(int)$_GET['edit']]);$edit=$q->fetch();}
$rows=$pdo->query("SELECT * FROM students ORDER BY student_id DESC")->fetchAll();
page_header('Student directory','students.php');
?>
<div class="toolbar"><div class="muted">Manage fictional student records and contact details.</div><a class="btn" href="#student-form">＋ Add student</a></div>
<section class="form-card" id="student-form"><h3><?=$edit?'Edit student':'Add a student'?></h3>
<form method="post"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=e($edit['student_id']??'0')?>">
<div class="form-grid">
<div class="field"><label>Student name *</label><input name="student_name" required value="<?=e($edit['student_name']??'')?>"></div>
<div class="field"><label>Email *</label><input type="email" name="email" required value="<?=e($edit['email']??'')?>"></div>
<div class="field"><label>Phone</label><input name="phone" value="<?=e($edit['phone']??'')?>"></div>
<div class="field"><label>Gender</label><select name="gender"><?php foreach(['Female','Male','Other'] as $g):?><option <?=$g===($edit['gender']??'Female')?'selected':''?>><?=$g?></option><?php endforeach;?></select></div>
<div class="field"><label>Course *</label><input name="course" required value="<?=e($edit['course']??'')?>"></div>
</div><div class="form-actions"><button class="btn">Save student</button><?php if($edit):?><a class="btn secondary" href="students.php">Cancel</a><?php endif;?></div></form></section>
<h3 class="section-title">Student records · <?=count($rows)?></h3><div class="table-wrap"><table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Gender</th><th>Course</th><th>Actions</th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=$r['student_id']?></td><td><?=e($r['student_name'])?></td><td><?=e($r['email'])?></td><td><?=e($r['phone'])?></td><td><?=e($r['gender'])?></td><td><?=e($r['course'])?></td><td><div class="actions"><a class="btn small secondary" href="?edit=<?=$r['student_id']?>">Edit</a><form method="post" onsubmit="return confirm('Delete this student?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['student_id']?>"><button class="btn small danger">Delete</button></form></div></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="7" class="empty">No students found.</td></tr><?php endif;?></tbody></table></div>
<?php page_footer(); ?>
