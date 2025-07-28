<?php

class PROJECTCARDS
{

    function make_card($postdata)
    {
        $carddata = '<div class="col w-25">';
        $carddata = $carddata . '   <div class="card h-100 w-100 d-inline-block">';
        $carddata = $carddata . '       <div class="card-body">';
        $carddata = $carddata . '           <h5 class="card-title">'.$postdata[1].'</h5>';
        $carddata = $carddata . '           <p class="card-text">'.$postdata[5].'</p>';
        $carddata = $carddata . '           <span class="align-bottom"><a href="editor.php?action=EDIT&id='.$postdata[0].'" class="btn btn-block btn-primary"><i class="bi bi-folder"></i> Open</a></span>';
        $carddata = $carddata . '           <span class="align-bottom"><button type="button" class="btn btn-block btn-danger delete-project-btn" data-project-id="'.$postdata[0].'" data-project-name="'.$postdata[1].'" data-toggle="modal" data-target="#deleteProjectModal"><i class="bi bi-trash"></i> Delete</button></span>';       
        $carddata = $carddata . '       </div>';
        $carddata = $carddata . '   </div>';
        $carddata = $carddata . '</div>';
        return $carddata;
    }

    function make_cards($posts)
    {
        $cards = '';
        $i = 0;
        $started = FALSE;
        //print_r($postdata);
        foreach ($posts as $postdata)  {
            
            if($i==0)
            {
                $cards = $cards . '<div class="row row-cols-4 align-items-center w-100 p-3">';
                $started = TRUE;
            }

            
            $cards = $cards . $this->make_card($postdata);
            $i++;
            
            
            if($i>=4)
            {
                $cards = $cards . '</div>';
                $started = FALSE;
            }
            
        }
        
        if($started==TRUE)
        {
            $cards = $cards . '</div>';
        }
        

        return $cards;
    }

}

?>