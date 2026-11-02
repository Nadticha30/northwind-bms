<?php
require_once "ConnDB.php";

print_r($_POST);

echo empty($_POST['price_filter']) ? "true" : "false";

// 1. Get price filter value (default to 0 if empty)
$price_filter = empty($_POST['price_filter']) ? 0 : $_POST['price_filter'];

// 2. Get operator value (default to >)
$operator = empty($_POST['operator']) ? '>' : $_POST['operator'];

// 3. Security check: only allow valid SQL comparison operators
$allowed_operators = ['>', '<', '=', '>=', '<='];
if (!in_array($operator, $allowed_operators)) {
    $operator = '>';
}

try {
// 4. Inject operator directly into SQL query
$sql = "SELECT i_ProductID, c_ProductName, i_Price
FROM tb_products
WHERE i_Price $operator $price_filter";

// Execute the SQL query
$result = $conn->query($sql);

$datas_counter = $result->rowCount();
$datas = $result->fetchAll(PDO::FETCH_ASSOC);

unset($result);

} catch(PDOException $e) {
echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
<!-- Latest compiled and minified CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
<div class="container mt-4">

<div class="row">
<div class="col-sm-1"></div>
<div class="col-sm-10">
   <h2>Hover Rows</h2>
   <p>The .table-hover class enables a hover state (grey background on mouse over) on table rows:</p>

 <form method="post" action="wk5_db2.php">
 <div class="row g-2 mb-3">
 
   <!-- Operator Select Dropdown -->
   <div class="col-sm-3">
     <select name="operator" class="form-select">
       <option value=">" <?= $operator == '>' ? 'selected' : '' ?>>Greater than (>)</option>
       <option value="<" <?= $operator == '<' ? 'selected' : '' ?>>Less than (<)</option>
       <option value="=" <?= $operator == '=' ? 'selected' : '' ?>>Equal to (=)</option>
       <option value=">=" <?= $operator == '>=' ? 'selected' : '' ?>>Greater than or equal to (>=)</option>
       <option value="<=" <?= $operator == '<=' ? 'selected' : '' ?>>Less than or equal to (<=)</option>
     </select>
   </div>

   <!-- Price Input Field -->
   <div class="col-sm-7">
     <input type="number" class="form-control" placeholder="please input price to filter" name="price_filter" value="<?= htmlspecialchars($price_filter) ?>">
   </div>

   <!-- Submit Button -->
   <div class="col-sm-2">
     <button type="submit" class="btn btn-primary w-100"> Submit </button>
   </div>

 </div>
 </form>

<table class="table table-hover">
<thead>
<tr>
<th>ProductID</th>
<th>ProductName</th>
<th>Price</th>
</tr>
</thead>
<tbody>
<?php
for($n=0;$n<$datas_counter;$n+=1){
echo "<tr>
<td>".$datas[$n]['i_ProductID']."</td>
<td>".$datas[$n]['c_ProductName']."</td>
<td>".$datas[$n]['i_Price']."</td>
</tr>";
}
?>
</tbody>
</table>

</div>
<div class="col-sm-1"></div>

</div>
</div>
</body>
</html>