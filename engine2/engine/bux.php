<?php
function getFiles() {
    return array_splice(scandir('doc'), 2);
}