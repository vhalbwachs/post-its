function drawNewNote() {
	$('<div />').html('<p>This is a note</p>')
				.addClass('note')
				.appendTo('#wrapper')
				.draggable({
					helper: function(){
						return $('.selected').draggable();
					}
				})
				.on('dblclick',editNote)
				.on('dragstop', sendNotes);
	sendNotes();
};

function prepDrawnNotes() {
	$('.note').draggable({
					helper: function(){
						return $('.selected').draggable();
					}
				})
			 .on('dblclick',editNote)
			 .on('dragstop', sendNotes)
			 .on('click', function() {$(this).addClass('selected')});
};

function getNotes() {
	var workspace_id = $('select').val();
	$('#wrapper').empty();
	$.ajax({
		url: "processnotes.php?action=getNotes",
		type: "GET",
		dataType: 'html',
		data: {workspace_id: workspace_id},
		success: function(data) {
			$('#wrapper').html(data);
			prepDrawnNotes();
		}
	})
}


function sendNotes() {
	var workspace_id = $('select').val();
	var html_content = $('#wrapper').html();
	$.ajax({
		url: "processnotes.php?action=saveNotes",
		type: "GET",
		dataType: 'text',
		data: {workspace_id: workspace_id, html: html_content},
	})
}

function editNote() {
	var newContent = prompt("New Note Text");
	$(this).html("<p>" + newContent + "</p>");
};

function populateWorkspace(id, name) {
	$('<option />').html(name)
				   .attr('value',id)
				   .appendTo('select')
}

function getWorkspaces() {
	$.ajax({
		url: "processnotes.php?action=getWorkspaces",
		type: "GET",
		dataType: 'json',
		success: function(data) {
			for (var i = 0; i < data.length; i++) {
				populateWorkspace(data[i]['id'], data[i]['workspace_name']);
			};
		}
	})
}

function createWorkspace() {
	var name = prompt("Workspace Name");
	if (name.length > 0) {
		$.ajax({
			url: "processnotes.php?action=createWorkspace",
			type: "GET",
			dataType: 'json',
			data: {name: name},
			success: function(id) {
				populateWorkspace(id,name);
				$('select').val(id);
			}
		})
	} else {
		alert("name cannot be blank");
	}
}

function deleteWorkspace() {
	var workspace_id = $('select').val();
	$.ajax({
		url: "processnotes.php?action=deleteWorkspace",
		type: "GET",
		data: {workspace_id: workspace_id},
		success: function() {
			$("#workspaces option:selected").remove();			
		}
	})		
}

$(function() {
	getWorkspaces();
	$('#new-post').on('click', drawNewNote);
	$('#new-workspace').on('click', createWorkspace);
	$('#delete-workspace').on('click', deleteWorkspace);
	$('select').change(getNotes);
	$('.note').on('click', function() {$(this).addClass('selected')});
	getNotes();
})