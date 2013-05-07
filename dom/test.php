<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<script> 
function searchFiles(){   
    var fso = new ActiveXObject("Scripting.FileSystemObject");   
    var f = fso.GetFolder(document.all.fixfolder.value);   
    var fc = new Enumerator(f.files);   
    var s = "";   
    for (; !fc.atEnd(); fc.moveNext())   
    {   
        s += fc.item();   
        s += "<br/>";   
    }   
    fk = new Enumerator(f.SubFolders);   
    for (; !fk.atEnd(); fk.moveNext())   
    {   
        s += fk.item();   
        s += "<br/>";   
    }   

        textarea.innerHTML = s 
}   
</script> 
</head> 
<body bgcolor="#FFFFFF"> 
<form action="file-upload.php" method="post" enctype="multipart/form-data">
  Send these files:<br />
  <input name="userfile[]" type="file" /><br />
  <input name="userfile[]" type="file" /><br />
  <input type="submit" value="Send files" />
</form>

</body> 
</html>