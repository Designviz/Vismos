<?php

class CARDBUILDER
{



    function make_card($postdata)
    {
        //--Row Begin
        $carddata = '<div class="row w-100">    
        <div class="col">';

        //--Card Begin
        $carddata = $carddata . '<div class="card flex-fill w-100 p-3">';

        //--Card Body Begin
        $carddata = $carddata . '<div class="card-body">';

        //Card Featured Image
        $carddata = $carddata . '<img src="..." class="card-img-top" alt="...">';

         //--Card Header Begin       
        $carddata = $carddata . '<div class="card-header">';  

        //--Posted by
        $carddata = $carddata . '<img src="..." class="rounded-circle img-thumbnailfloat-start" alt="...">';
        $carddata = $carddata . '<h5 class="card-title">User</h5>';
        $carddata = $carddata . '<h6 class="card-subtitle mb-2 text-muted">'.$postdata[11].'</h6>';
       
        //--Card Header End
        $carddata = $carddata . '</div>';
        
         //--Card Content
         $carddata = $carddata . '</div>';


         $parser = new \DBlackborough\Quill\Parser\Html();
         $renderer = new \DBlackborough\Quill\Renderer\Html();
         
         try {
            $quill = new \DBlackborough\Quill\Render($postdata[2]);
            $result = $quill->render();
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        
        $carddata = $carddata .$result;

/*



  */      
      
        //--Card Footer Begin
        $carddata = $carddata . '<div class="card-footer">';

        $carddata = $carddata . '</div>';
        //--Card Footer End

        //--Card Body End
        $carddata = $carddata . '</div>';

        //--Card End
        $carddata = $carddata . '</div>';

        //--Row End
        $carddata = $carddata . '</div>    
        </div> ';

        return $carddata;
    }

    function make_cards($posts)
    {
        $cards = '';
        foreach ($posts as $postdata)  {
            $cards = $cards . $this->make_card($postdata);
        }
        return $cards;
    }

}

?>