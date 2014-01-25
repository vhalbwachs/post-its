<?php 
require "conn.php";
session_start();


function getNotes($conn, $workspace)
{
	$query = "SELECT notes_content FROM notes WHERE id =".$workspace;
	$result = mysqli_query($conn, $query);
	$notes = mysqli_fetch_row($result);
	return $notes[0];
}

function saveNotes($conn, $workspace, $data) 
{
	$query = "UPDATE notes SET notes_content = '".$data."' WHERE id =".$workspace;
	$result = mysqli_query($conn, $query);
}

function createWorkspace($conn, $name) 
{
	$query = "INSERT INTO notes (workspace_name) VALUES ('".$name."')";
	mysqli_query($conn, $query);
	$new_workspace_id = mysqli_insert_id($conn);
	return $new_workspace_id;
}

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

function deleteWorkspace($conn, $workspace_id) {
	$query = "DELETE FROM notes where id =".$workspace_id;
	mysqli_query($conn, $query);
}


switch ($_GET['action']) {
    case "getWorkspaces":
        echo json_encode(getWorkspaces($conn));
        break;
    case "saveNotes":
        saveNotes($conn, $_GET['workspace_id'], $_GET['html']);
        break;
    case "getNotes":
        echo getNotes($conn, $_GET['workspace_id']);
        break;
    case "createWorkspace":
    	echo createWorkspace($conn, $_GET['name']);
    	break;
    case "deleteWorkspace":
    	deleteWorkspace($conn, $_GET['workspace_id']);

}
?>