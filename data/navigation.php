<?php

$aSections = array(

// anonymous
    array (
      'title'           => 'Contact Us',
      'parent_id'       => SGL_NODE_USER,
      'uriType'         => 'dynamic',
      'module'          => 'enquiry',
      'manager'         => 'EnquiryMgr.php',
      'actionMapping'   => 'form',
      'add_params'      => 'type/ContactUs',
      'is_enabled'      => 1,
      'perms'           => "SGL_GUEST, SGL_MEMBER, SGL_ADMIN",
        ),
    );
?>
