<!-- Các hàm xử lý database -->
<?php
if(!defined('_CODE')){
    die('Access denied...');
}

function query($sql, $data=[],$check = false){
    global $conn;
    $status = false;
    try{
        $statement = $conn -> prepare($sql);
    
        if(!empty($data)){
            $status = $statement->execute($data);
        }
        else{
            $status = $statement->execute();
        }
        
    
    }catch(Exception $e){
        echo $e->getMessage();
        die();
    }

    if($check){
        return $statement;
    }
    return $status;
}

// Hàm insert vào data
function insert($table,$data){
    $key = array_keys($data); //Lấy key trong mảng
    $listKey = implode(',',$key); //Nối key lại với nhau bằng dấu ,
    $value = ':'.implode(',:',$key);

    $sql = 'INSERT INTO ' .$table. '('.$listKey .')'. 'VALUES ('. $value .')';
    $result = query($sql,$data);
    return $result;
}

//Hàm update 
function update($table,$data,$condition=''){
    $update = '';
    foreach($data as $key => $value){
        $update .= $key .'= :'.$key.',';
    }
    
    $update = trim($update,','); // Xóa dấu phẩy ở cuối

    // Kiểm tra có tồn tại điều kiện hay không
    if(!empty($condition)){
        $sql = 'UPDATE '. $table .' SET '. $update .' WHERE '.$condition;
    }else{
        $sql = 'UPDATE '. $table .' SET '. $update;
    }
    $result = query($sql,$data);
    return $result;
}

// Hàm Delete

function delete($table,$condition = ''){
    if(empty($condition)){
        $sql = 'DELETE FROM ' .$table;
    }
    else{
        $sql = 'DELETE FROM ' .$table. ' WHERE ' . $condition;
    }
    $result = query($sql);
    return $result;
}

// Lấy nhiều dòng dữ liệu
function getRaw($sql){
    $result = query($sql,'',true);
    if(is_object($result)){
        $dataFetch = $result->fetchAll(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}


// Lấy 1 dòng dữ liệu
function oneRaw($sql){
    $result = query($sql,'',true);
    if(is_object($result)){
        $dataFetch = $result->fetch(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
// Đếm số dòng dữ liệu
function getRows($sql){
    $result = query($sql,'',true);
    if(!empty($result)){
        return $result -> rowCount();
    }
}