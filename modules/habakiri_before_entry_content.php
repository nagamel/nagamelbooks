<?php 
$arg_bookfield = array(
    'name' => '書名',
    'author' => '著者',
    'publisher' => '出版社',
    'releasedate' => '発売日',
);
if(is_single()){
    $bookfield = list_bookfield('field_',$arg_bookfield);
    if(!empty($bookfield)){
        echo '<div class="bookfield">' ."\n" .'<ul>' ."\n";
        foreach($bookfield as $item){
            echo $item;
        }
        echo '</ul>' ."\n" .'</div>' ."\n";
    }else{
         
    }
}
?>