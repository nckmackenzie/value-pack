<?php
function action_buttons( $action = "", $path = "", $id = 0) {
    if($action === 'edit'){
        echo "<a href=".URLROOT."/$path/edit/$id class='group'>
                <span class='text-emerald-400 transition-colors font-medium text-xs group-hover:text-emerald-300'>Edit</span>
              </a>";
    }else if($action === "delete"){
        echo "<button type='button' class='group btndel outline-none focus:outline-none focus:border-none' data-id='$id'>
                <span class='text-rose-400 transition-colors font-medium text-xs group-hover:text-rose-300'>Delete</span>
              </button>";
    }
}

function DeleteModal($route,$modalid,$message,$inputid){
    echo "
       <div class='modal fade' id='$modalid' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='exampleModalLongTitle'>Delete Transaction</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        <form action='$route' method='post'>
                            <div class='row'>
                                <div class='col-12'>
                                    <label for='$inputid'>$message</label>
                                    <input type='hidden' name='id' id='$inputid'>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                                <button type='submit' class='btn btn-danger'>Yes</button>
                            </div>
                        </form>
                    </div>       
                </div>
            </div>
        </div>
    ";
}