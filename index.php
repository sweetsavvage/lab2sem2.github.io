<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>  
<style>
          
            div {
               border: 1px solid black;
               padding: 5px;
            }
         
        </style>

<script>
const ajax = new XMLHttpRequest();

function getLiteratureByPublishers(){
  let publisher = document.getElementById("publisher").value;

    ajax.onreadystatechange = updatePublishers;
    ajax.open("GET", "getLiteratureByPublishers.php?publisher="+ publisher);
    ajax.send(null);
}

  function updatePublishers(){
    if(ajax.readyState === 4){
      if(ajax.status === 200){
        let text = document.getElementById('publishers-table');
        let res = ajax.responseText;
        let resHtml ="";
        let newReq = [];
    
        let lastReqHtml ="";
        let lastReq = JSON.parse(localStorage.getItem("publishers"));

        res = JSON.parse(res);
        res.forEach(vendor =>{
         resHtml += "<tr><td style = 'border: 1px solid'>" + vendor +"</td></tr>";
         newReq.push(vendor);
        });

      if(lastReq == null){
        lastReqHtml +="<tr><td style = 'border: 1px solid'> there are no recent reqs </td></tr>";
        document.getElementById("publishersRecent-table").innerHTML = lastReqHtml;
      }else{
        lastReq.forEach(vendor =>{
        lastReqHtml +="<tr><td style = 'border: 1px solid'>" + vendor +"</td></tr>";
      });
        document.getElementById("publishersRecent-table").innerHTML = lastReqHtml;
    }   
      localStorage.setItem("publishers", JSON.stringify(newReq)); 
      text.innerHTML = resHtml;
      }
    }
  }

function getLiteratureByYears(){
let startYear = document.getElementById("startYear").value;
let finalYear = document.getElementById("finalYear").value;

ajax.onreadystatechange = updateYears;
ajax.open("GET", "getLiteratureByYears.php?startYear="+ startYear +"&finalYear=" + finalYear);
ajax.send(null);
}

function updateYears(){
if(ajax.readyState === 4){
  if(ajax.status === 200){
    let text = document.getElementById('Years-table');
    let res = ajax.responseText;
    let resHtml ="";
    let newReq = [];
    
    let lastReqHtml ="";
    let lastReq = JSON.parse(localStorage.getItem("years"));
    
    res = JSON.parse(res);
    res.forEach(literature =>{
    resHtml += "<tr><td style = 'border: 1px solid'>" + literature +"</td></tr>";
    newReq.push(literature);
    });
    
    if(lastReq == null){
        lastReqHtml +="<tr><td style = 'border: 1px solid'> there are no recent reqs </td></tr>";
        document.getElementById("YearsRecent-table").innerHTML = lastReqHtml;
    }else{
        lastReq.forEach(literature =>{
        lastReqHtml +="<tr><td style = 'border: 1px solid'>" + literature +"</td></tr>";
     });
        document.getElementById("YearsRecent-table").innerHTML = lastReqHtml;
    }    
    localStorage.setItem("years", JSON.stringify(newReq)); 
    text.innerHTML = resHtml;
  }
}
}

function getLiteratureByAuthor(){
  let author = document.getElementById("author").value;

ajax.onreadystatechange = updateAuthor;
ajax.open("GET", "getLiteratureByAuthor.php?author=" + author);
ajax.send(null);
}

function updateAuthor(){
if(ajax.readyState === 4){
  if(ajax.status === 200){
    let text = document.getElementById('Author-table');
    let res = ajax.responseText;
    let newReq = [];
    let lastReqHtml ="";
    let lastReq = JSON.parse(localStorage.getItem("Author"));
    let resHtml ="";

    res = JSON.parse(res);
    
    res.forEach(literature =>{
     resHtml += "<tr><td style = 'border: 1px solid'>" + literature +"</td></tr>";
     newReq.push(literature);
    });

    if(lastReq == null){
        lastReqHtml +="<tr><td style = 'border: 1px solid'> there are no recent reqs </td></tr>";
        document.getElementById("AuthorResent-table").innerHTML = lastReqHtml;
    }else{
        lastReq.forEach(literature =>{
        lastReqHtml +="<tr><td style = 'border: 1px solid'>" + literature +"</td></tr>";
     });
        document.getElementById("AuthorResent-table").innerHTML = lastReqHtml;
    }    

  localStorage.setItem("Author", JSON.stringify(newReq));
  text.innerHTML = resHtml;
  }
}
}
</script>
</head>
<body>

<div id = "publishers-div">

  <form name ="publishers">
  <lable>Литература по издательству: </lable>

  <?php 
    require_once __DIR__ . "/vendor/autoload.php";
    $collection = (new MongoDB\Client) -> dbforlab -> literature;
    $cursor = $collection -> find();

    echo "<select id = 'publisher'>";
    foreach($cursor as $lit){
      echo "<option value = '".$lit["publisher"]."'>".$lit["publisher"]."</option>";
    }
    echo "</select>";
  ?>

  <input type="button" onclick = "getLiteratureByPublishers()" value="ОК">
  </form> 
  <table style="border: 1px solid"><tr><th> Название </th></tr>
  <tbody id = "publishers-table"></tbody>
  </table>
  <table style="border: 1px solid"><tr><th> Последний запрос </th></tr>
  <tbody id = "publishersRecent-table"></tbody>
  </table>
</div>


<div id ="year-range-div">

  <form name ="year">
    <lable>Литература за определеный период: </lable>
  <?php 
  // устанавливаем первый и последний год диапазона
  $yearArr = range(2000, 2020);

  echo "<select id= 'startYear'><option> Начало выборки </option>";

    foreach ($yearArr as $year) {
    echo '<option '.$year.' value="'.$year.'">'.$year.'</option>';
  }
    echo "</select>";

  echo "<select id='finalYear'><option> Конец выборки </option>";

    foreach ($yearArr as $year) {
        echo '<option '.$year.' value="'.$year.'">'.$year.'</option>';
    }
    echo "</select>";
  ?>
    <input type="button" onclick = "getLiteratureByYears()" value="ОК">
</form> 


<table style="border: 1px solid"><tr><th> Название </th></tr>
  <tbody id = "Years-table"></tbody>
  </table>
  <table style="border: 1px solid"><tr><th> Последний запрос </th></tr>
  <tbody id = "YearsRecent-table"></tbody>
  </table>
</div>
<div id = "author-div">

<form name ="author">
    <lable>Литература по автору: </lable>
    <?php 
    require_once __DIR__ . "/vendor/autoload.php";
    $collection = (new MongoDB\Client) -> dbforlab -> literature;
    $cursor = $collection -> find();

    echo "<select id = 'author'>";
    foreach($cursor as $lit){
      echo "<option value = '".$lit["author"]."'>".$lit["author"]."</option>";
    }
    echo "</select>";
  ?>
    <input type="button" onclick = "getLiteratureByAuthor()" value="ОК">
</form> 

<table style="border: 1px solid"><tr><th> Название </th></tr>
<tbody id = "Author-table"></tbody>
</table>

<table style="border: 1px solid"><tr><th> Последний запрос: </th></tr>
<tbody id = "AuthorResent-table"></tbody>
</table>

</div>



</body>
</html>
