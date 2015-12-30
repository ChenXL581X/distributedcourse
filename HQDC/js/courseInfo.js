$(document).ready(function(){ 
	var editor = new Simditor({
		  textarea: $('#editor'),
		  placeholder: '',
		  defaultImage: 'images/image.png',
		  params: {},
		  upload: false,
		  tabIndent: true,
		  toolbar: true,
		  toolbarFloat: true,
		  toolbarFloatOffset: 0,
		  toolbarHidden: false,
		  pasteImage: false,
		  cleanPaste: false
		});
	$("#course_info").change(function() {
		editor.setValue("");
	});
	$("#button").click(function() {
		var choose = $('#course_info').val();
		var context = editor.getValue();
		if(context == "") {
			if(confirm("您并没有输入内容，确认继续提交？")) {
				submit(choose, context);
			}
		}
		else {
			submit(choose, context);
		}
		
	});
});

function submit(choose, context) {
	$.ajax({
		url: "courseInfo_run.php",
		data: {
			choose: choose,
			context: context
		},
		type : "POST",
		
		success : function(result) {
			console.log(result);
			result = JSON.parse(result);
			var message = result.message;
			if(result.state == 'success') {
				
				alert(message);
			}
			else {
				alert(message);
			}
		}
	});
}