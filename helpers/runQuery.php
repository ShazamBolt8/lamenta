<?php
function runQuery($conn, $query, $values = [])
{
  $stmt = $conn->prepare($query);
  if (!$stmt) {
    die("Statement preparation failed: $conn->error");
  }

  if (!empty($values)) {
    $types = "";
    foreach ($values as $value) {
      if (is_int($value)) {
        $types .= "i";
      } elseif (is_float($value)) {
        $types .= "d";
      } elseif (is_string($value)) {
        $types .= "s";
      } else {
        $types .= "b";
      }
    }
    $stmt->bind_param($types, ...$values);
  }

  if (!$stmt->execute()) {
    die("Statement execution failed: " . $stmt->error);
  }

  $result = $stmt->get_result();
  if ($result === false) {
    return ['rows' => 0, 'data' => []];
  }

  $data = [];
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  $num_rows = count($data);

  $stmt->close();

  return ['rows' => $num_rows, 'data' => $data];
}