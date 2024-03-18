<?php
namespace heroic\craftlightswitchviews;
use Craft;
use craft\base\Element;
use craft\base\Plugin;
use craft\events\SetElementTableAttributeHtmlEvent;
use craft\helpers\Html;
use yii\base\Event;
use yii\base\Module;
use craft\web\View;

/**
 * lightswitch-views module
 *
 * @method static lightswitchviews getInstance()
 */
class LightswitchViews extends Plugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = false;

    public static function config(): array
    {
        return [
            'components' => [
                // Define component configs here...
            ],
        ];
    }


    public function init(): void
    {
        parent::init();

        Craft::setAlias('@craftlightswitchviews', __DIR__);


        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            //$this->controllerNamespace = 'modules\\console\\controllers';
            $this->controllerNamespace = 'modules\\controllers';
        } else {
            $this->controllerNamespace = 'modules\\controllers';
        }

        Event::on(
            View::class,
            View::EVENT_END_BODY,
            function () {
                if (\Craft::$app->request->isCpRequest) {
                    Craft::$app->getView()->registerAssetBundle(LightswitchViewsBundle::class);
                }
            }
        );

        Event::on(
            Element::class,
            Element::EVENT_SET_TABLE_ATTRIBUTE_HTML,
            function (SetElementTableAttributeHtmlEvent $event) {
                // ALL LIGHTSWITCH FIELDS
                if(!str_contains($event->attribute, 'field:')){
                    return;
                }

                $uid = str_replace('field:', '', $event->attribute);
                $field = Craft::$app->fields->getFieldByUid($uid);
                if(get_class($field) != 'craft\fields\Lightswitch'){
                    return;
                }
                $value = $event->sender->getFieldValue($field->handle);
                if (!$value) {
                    $event->html = Html::tag('span', '', [
                        'class' => 'checkbox-icon unchecked checkbox-hook-'.$field->handle,
                        'fieldname' => $field->handle
                    ]);
                } else{
                    $label = $field->onLabel ?: Craft::t('app', 'Enabled');
                    $event->html = Html::tag('span', '', [
                        'class' => 'checkbox-icon checkbox-hook-'.$field->handle,
                        'role' => 'img',
                        'title' => $label,
                        'aria' => [
                            'label' => $label,
                        ],
                        'fieldname' => $field->handle
                    ]);
                }
            }
        );
    }
}
