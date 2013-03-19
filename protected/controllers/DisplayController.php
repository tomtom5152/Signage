<?php

class DisplayController extends Controller 
{
        public $layout = false;
        
        /**
         * Checks if the display has a hash, if so update the expire date, else
         * create a new one based on a 32 char random string.
         * Send the code required to display content to the screen.
         */
        public function actionIndex() {
                $screenhash = Yii::app()->request->cookies->contains('screenhash') ?
                        Yii::app()->request->cookies['screenhash']->value : false;
                
                if(!$screenhash) {
                        $screenhash = Randomness::randomString(32);
                        $model=new Screen;
                        $model->screenname = Yii::app()->dateFormatter->formatDateTime(time(), 'full', 'full');
                        $model->location = null;
                        $model->hash = $screenhash;
                        $model->save();
                } 
                
                Yii::app()->request->cookies['screenhash'] = new CHttpCookie('screenhash', $screenhash, array(
                    'expire'    => strtotime('+ 5 years'),
                ));
                
                $this->render('screen');
        }
        
        /**
         * Display content of a specific type for this screen
         * @param String $type The content type requested
         * @throws CHttpException If the screen isnt registered or it isnt AJAX
         */
        public function actionContent($type) {
                $hash = Yii::app()->request->cookies->contains('screenhash') ?
                        Yii::app()->request->cookies['screenhash']->value : false;
                
                if(!$hash || !Yii::app()->request->isAjaxRequest)
                        throw new CHttpException(403,'This action is only allowed via AJAX with a saved screen hash.');
                
                $screen;
                $screen = Screen::model()->findByAttributes(array('hash'=>$hash));
                $output = new stdClass();
                
                // Specific non-database options
                switch($type) {
                        case 'time':
                                $output->content = Yii::app()->dateFormatter->formatDateTime(time(), 
                                        Yii::app()->params['clock']['hour'],
                                        Yii::app()->params['clock']['min']
                                );
                                $output->duration = Yii::app()->params['clock']['interval'];
                                break;
                        
                        case 'weather':
                                // Get weather from accuweather using forecastfox
                                $location = Yii::app()->params['weather']['location'];
                                $metric = (int)Yii::app()->params['weather']['metric'];
                                
                                $url = "http://wwwa.accuweather.com/adcbin/forecastfox/weather_data.asp?location=$location&metric=$metric";
                                
                                $ch = curl_init();
                                $timeout = 0;
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                                $file_contents = curl_exec($ch);
                                curl_close($ch);
                                
                                $xml = simplexml_load_string($file_contents);
                                
                                $output->content = $this->renderPartial('weather', array('xml'=>$xml), true);
                                $output->duration = Yii::app()->params['weather']['interval'];
                                break;
                                
                        default:
                                $feeds = $screen->screenFeeds;
                                $idfeeds = array();
                                foreach($feeds as $feed) {
                                        $idfeeds[]=$feed->idfeed;
                                }

                                $content = Content::model()->findAllByAttributes(
                                        array(
                                            'idfeed'=>$idfeeds,
                                            'approved'=>true,
                                            'content_type'=>$type,
                                        ),
                                        array(
                                            'condition'=>'`start`<=:date AND `end`>=:date',
                                            'params'=>array(
                                                ':date'=>date('Y-m-d'),
                                        ),
                                ));
                                
                                if(!empty($content)) {
                                        shuffle($content);
                                        $toShow = $content[0];
                                        
                                        if($type=='img') {
                                                $output->content = '<img src="'.Yii::app()->getAssetManager()->publish("protected/assets/content/img/{$toShow->content}").'" />';
                                        } else {
                                                $output->content = $toShow->content;
                                        }
                                        $output->duration = $toShow->duration;
                                } else {
                                        $output->content = '';
                                        $output->duration = 5;
                                }
                                break;
                }
                echo json_encode($output);
        }
}

?>
