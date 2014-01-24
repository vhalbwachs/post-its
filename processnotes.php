<?php 
require "conn.php";
session_start();


function getNotes($conn, $workspace)
{
	$query = "SELECT * FROM notes WHERE id =".$workspace;
	$result = mysqli_query($conn, $query);
	$notes = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$notes[] = $row;
	}
	return $notes;
}

function saveNotes($conn, $workspace, $data) 
{
	$query = "UPDATE notes SET notes_content = '".$data."' WHERE id =".$workspace;
	$result = mysqli_query($conn, $query);
}

// function createWorkspace($name) 
// {
// 	$query = "INSERT INTO notes (workspace) VALUES (".$name.")";
// }

function getWorkspaces($conn) 
{
	$query = "SELECT id, workspace_name FROM notes";
	$result = mysqli_query($conn, $query);
	$workspaces = array();
	while ($row = mysqli_fetch_assoc($result)) {
		$workspaces[] = $row;
	}
	return $workspaces;
}


switch ($_GET['action']) {
    case "getWorkspaces":
        echo json_encode(getWorkspaces($conn));
        break;
    case "saveNotes":
        saveNotes($conn, $_GET['workspace_id'], json_encode($_GET['notes']));
        break;
    case "getNotes":
        echo json_encode(getNotes($conn, $_GET['workspace_id']));
        break;
}
?>