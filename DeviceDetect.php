<?php

namespace israelfl\devicedetect;

use Yii;
use Detection\MobileDetect;
use yii\base\Component;

/**
 * DeviceDetect
 *
 * @author Israel Falcón <israel.falcon.android@gmail.com>
 * @version 0.0.1
 */

class DeviceDetect extends Component {

    private $_mobileDetect;

    // Automatically set view parameters based on device type
    public $setParams = true;

    // Automatically set alias parameters based on device type
    public $setAlias = true;

    public function __call( $name, $parameters )
    {
        return call_user_func_array(
                array( $this->_mobileDetect, $name ),
                $parameters
        );
    }

    public function __construct( $config = array() )
    {
        parent::__construct( $config );
    }

    public function init()
    {
        $this->_mobileDetect = new MobileDetect();
        parent::init();

        if ( $this->setParams )
        {

            Yii::$app->params['devicedetect'] = [
                'isMobile' => false,
                'isTablet' => false,
                'isDesktop' => false,
                'os' => ''
            ];

            if ( $this->_mobileDetect->isTablet() ) Yii::$app->params['devicedetect']['isTablet'] = true;
            elseif ( $this->_mobileDetect->isMobile() ) Yii::$app->params['devicedetect']['isMobile'] = true;
            else Yii::$app->params['devicedetect']['isDesktop'] = true;

            if ( $this->_mobileDetect->isiOS() ) Yii::$app->params['devicedetect']['os'] = 'ios';
            elseif ( $this->_mobileDetect->isAndroidOS() ) Yii::$app->params['devicedetect']['os'] = 'android';
            elseif ( $this->_mobileDetect->isWindowsMobileOS() || $this->_mobileDetect->isWindowsPhoneOS() ) Yii::$app->params['devicedetect']['os'] = 'wphone';

            Yii::$app->params['devicedetect']['isiOS'] = $this->_mobileDetect->isiOS();
            Yii::$app->params['devicedetect']['isAndroid'] = $this->_mobileDetect->isAndroidOS();
            Yii::$app->params['devicedetect']['isWindowsPhone'] = $this->_mobileDetect->isWindowsMobileOS() || $this->_mobileDetect->isWindowsPhoneOS();

        }

        if ( $this->setAlias )
        {

            if ( $this->_mobileDetect->isTablet() ) Yii::setAlias( '@device', 'tablet' );
            elseif ( $this->_mobileDetect->isMobile() ) Yii::setAlias( '@device', 'mobile' );
            else Yii::setAlias( '@device', 'desktop' );

        }
    }

}
