INSERT INTO module VALUES ({SGL_NEXT_ID}, 1, 'enquiry', 'Contact Enquiry', 'The ''Contact Enquiry'' module allows users to submit enquiries for administrators. The enquiry forms based on content types which you can build with cms module. Finally, module will log, email or do other with submissions, depend on observer(s) you select.', NULL, '48/module_contactus.png', 'Y Viktorov, A Viktorov', NULL, 'BSD', 'beta');

--
-- "ContactUs" content type
--

-- insert content type
INSERT INTO `content_type` VALUES ({SGL_NEXT_ID}, 'ContactUs');

-- get content type id
SELECT @contentTypeIdContactus := content_type_id FROM `content_type` WHERE name = 'ContactUs';

-- insert attributes
INSERT INTO `attribute` VALUES ({SGL_NEXT_ID}, 2, @contentTypeIdContactus, 'comment', 'Comment', '', 'a:1:{s:15:"attributeListId";s:0:"";}');

