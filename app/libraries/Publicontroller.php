<?php
class Publicontroller extends Controller {
    protected function should_skip_authentication() {
        return true;
    }
}
