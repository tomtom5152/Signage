<?php 
/**
 * Three form instances, along with the JS to control the Drag and Drop upload
 * @var $this ContentController
 * @var Content $model
 */
$this->pageTitle=Yii::t('signage','addcontent');
$assets=Yii::app()->getAssetManager()->publish('protected/assets', false, -1, YII_DEBUG);
Yii::app()->clientScript->registerScriptFile($assets.'/scripts/jquery.maxlength.js');
Yii::app()->clientScript->registerScriptFile($assets.'/scripts/jquery.filedrop.js');

if(empty($model->duration)) $model->duration = 5;
if(empty($model->start)) $model->start = date('Y-m-d');
if(empty($model->end)) $model->end = date('Y-m-d',strtotime('+1 month'));
?>
<div id="tabs">
        <ul>
                <li><a href="#ticker"><?php echo Yii::t('signage', 'content_ticker'); ?></a></li>
                <li><a href="#img"><?php echo Yii::t('signage', 'content_img'); ?></a></li>
                <li><a href="#text"><?php echo Yii::t('signage', 'content_text'); ?></a></li>
        </ul>
        
        <div class="form" id="ticker">
        <?php
        $form=$this->beginWidget('CActiveForm', array(
                'id'=>'content-form-ticker',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>true,
        ));
        $model->content_type = 'ticker';
        echo $form->hiddenField($model,'content_type');
        ?>

                <p class="note">Fields with <span class="required">*</span> are required.</p>

                <?php echo $form->errorSummary($model); ?>

                <div class="row">
                        <?php echo $form->labelEx($model,'content'); ?>
                        <?php echo $form->textArea($model,'content',
                                array(
                                    'maxlength'=>'140',
                                    'cols'=>'50',
                                    'rows'=>'3',
                                    'required'=>'required'
                        )); ?>
                        <div><span id="tickerRemaining"></span> <?php echo Yii::t('signage','ticker_remaining'); ?></div>
                        <?php echo $form->error($model,'content'); ?>
                </div>

                <?php $this->renderPartial('_options',array('model'=>$model,'form'=>$form)); ?>

        <?php $this->endWidget(); unset($form); ?>
        </div>
        
        <div class="form" id="text">
        <?php
        $form=$this->beginWidget('CActiveForm', array(
                'id'=>'content-form-text',
                'enableAjaxValidation'=>true,
                'enableClientValidation'=>true,
        ));
        $model->content_type = 'text';
        echo $form->hiddenField($model,'content_type');
        ?>

                <p class="note">Fields with <span class="required">*</span> are required.</p>

                <?php echo $form->errorSummary($model); ?>

                <div class="row">
                        <?php echo $form->labelEx($model,'content'); ?>
                        <?php echo $form->textArea($model,'content',
                                array(
                                    'cols'=>'50',
                                    'required'=>'required'
                        )); ?>
                        <?php echo $form->error($model,'content'); ?>
                </div>

                <?php $this->renderPartial('_options',array('model'=>$model,'form'=>$form)); ?>

        <?php $this->endWidget(); unset($form); ?>
        </div>
        
        <div class="form" id="img">
        <?php
        $form=$this->beginWidget('CActiveForm', array(
                'id'=>'content-form-img',
                'enableAjaxValidation'=>false,
                'enableClientValidation'=>true,
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
        $model->content_type = 'img';
        echo $form->hiddenField($model,'content_type');
        echo $form->hiddenField($model,'imgs');
        ?>

                <p class="note">Fields with <span class="required">*</span> are required.</p>

                <?php echo $form->errorSummary($model); ?>
                
                <div id="dropbox">
                        <span class="message">Drop images here to upload</span>
                </div>

                <div class="row">
                        <?php echo $form->labelEx($model,'img'); ?>
                        <?php echo $form->fileField($model,'img'); ?>
                        <?php echo $form->error($model,'img'); ?>
                </div>

                <?php $this->renderPartial('_options',array('model'=>$model,'form'=>$form)); ?>

        <?php $this->endWidget(); unset($form); ?>
        </div>
</div>

<script>
        $(document).ready(function() {
                $( "#tabs" ).tabs();
                $('#ticker textarea').maxlength({
                        'feedback': '#tickerRemaining'
                });
                
                var dropbox = $('#dropbox'),
                message = $('.message', dropbox);

                dropbox.filedrop({
                    // The name of the $_FILES entry:
                    paramname:'Content[img]',

                    maxfiles: 50,
                    maxfilesize: 5, // in mb
                    url: '<?php echo $this->createUrl('content/postimg'); ?>',

                    uploadFinished:function(i,file,response){
                        $.data(file).addClass('done');
                        // response is the JSON object that post_file.php returns
                        $('input#img').remove();
                        $imgs = $('input[name="Content[imgs]"]');
                        if($imgs.val() == "")
                                $imgs.val("[]");
                        var val = JSON.parse($imgs.val());
                        val.push(response);
                        $imgs.val(JSON.stringify(val));
                    },

                    error: function(err, file) {
                        switch(err) {
                            case 'BrowserNotSupported':
                                showMessage('Your browser does not support HTML5 file uploads!');
                                break;
                            case 'TooManyFiles':
                                alert('Too many files! Please select 50 at most!');
                                break;
                            case 'FileTooLarge':
                                alert(file.name+' is too large! Please upload files up to 2mb.');
                                break;
                            default:
                                break;
                        }
                    },

                    // Called before each upload is started
                    beforeEach: function(file){
                        if(!file.type.match(/^image\//)){
                            alert('Only images are allowed!');

                            // Returning false will cause the
                            // file to be rejected
                            return false;
                        }
                    },

                    uploadStarted:function(i, file, len){
                        createImage(file);
                    },

                    progressUpdated: function(i, file, progress) {
                        $.data(file).find('.progress').width(progress+'%');
                    }

                });

                var template = '<div class="preview">'+
                                    '<span class="imageHolder">'+
                                        '<img />'+
                                        '<span class="uploaded"></span>'+
                                    '</span>'+
                                    '<div class="progressHolder">'+
                                        '<div class="progress"></div>'+
                                    '</div>'+
                                '</div>'; 

                function createImage(file){

                    var preview = $(template),
                        image = $('img', preview);

                    var reader = new FileReader();

                    image.width = 100;
                    image.height = 100;

                    reader.onload = function(e){

                        // e.target.result holds the DataURL which
                        // can be used as a source of the image:

                        image.attr('src',e.target.result);
                    };

                    // Reading the file as a DataURL. When finished,
                    // this will trigger the onload function above:
                    reader.readAsDataURL(file);

                    message.hide();
                    preview.appendTo(dropbox);

                    // Associating a preview container
                    // with the file, using jQuery's $.data():

                    $.data(file,preview);
                }

                function showMessage(msg){
                    message.html(msg);
                };
        });
</script>