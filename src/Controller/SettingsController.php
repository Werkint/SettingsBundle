<?php
namespace Werkint\Bundle\SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Werkint\Bundle\FrameworkExtraBundle\Annotation as Rest;

/**
 * SettingsController.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 *
 * @Rest\Route("/settings")
 */
class SettingsController extends Controller
{
    // -- Services ---------------------------------------

    protected function serviceSettingsRepo()
    {
        return $this->get('werkint.repo.setting');
    }

    protected function serviceSettingsCompiler()
    {
        return $this->get('werkint.settings.compiler');
    }

    protected function serviceTreebuilder()
    {
        return $this->get('werkint.settings.treebuilder');
    }

    protected function serviceEncrypter()
    {
        return $this->get('werkint.settings.encrypter');
    }

    protected function serviceWebscript()
    {
        return $this->get('werkint.webscript');
    }

    // -- Actions ---------------------------------------

    /**
     * @Rest\Get("", name="werkint_settings")
     * @Rest\View()
     */
    public function indexAction()
    {
        $nodes = $this->serviceTreebuilder()->getTree();
        $this->serviceWebscript()->attach('tinymce');

        return [
            'nodes' => $nodes,
        ];
    }

    /**
     * @Rest\Xmlhttp("/save", name="werkint_settings_savesetting")
     * @Rest\View()
     */
    public function savesettingAction(Request $req)
    {
        $setting = $this->serviceSettingsRepo()->find(
            (int)$req->request->get('sid')
        );
        $data = $req->request->get('value');
        switch ($setting->getType()->getClass()) {
            case 'bool':
                $setting->setValue((int)(bool)$data);
                break;
            case 'int':
                $setting->setValue((int)$data);
                break;
            default:
                $setting->setValue($data);
        }
        $setting->setValue($this->serviceEncrypter()->keyCrypt(
            $setting->getValue(),
            $setting->getId()
        ));
        $this->getDoctrine()->getManager()->flush();

        $response = [
            'value' => $this->serviceEncrypter()->keyDecrypt(
                    $setting->getValue(), $setting->getId()
                ),
        ];

        return $response;
    }

    /**
     * @Rest\Xmlhttp("/update", name="werkint_settings_update")
     * @Rest\View()
     */
    public function updateAction()
    {
        $this->serviceSettingsCompiler()->compile();
        $envs = $this->container->getParameter('werkint_settings_envs');
        $command = '';
        $path = $this->container->getParameter('kernel.root_dir');
        foreach ($envs as $env) {
            $command .= 'php ' . $path . '/console cache:clear --env=' . $env . ';';
        }
        exec($command, $ret);

        return [];
    }

    /**
     * @Rest\Xmlhttp("/array/node/add", name="werkint_settings_addarraynode")
     * @Rest\View()
     */
    public function arrayNodeAddAction(Request $req)
    {
        $sid = $req->request->get('sid');
        $setting = $this->serviceSettingsRepo()->findRow($sid);
        $this->serviceSettingsRepo()->newSettingChild($setting);

        return ['sid' => $sid];
    }


    /**
     * @Rest\Xmlhttp("/array/node/remove", name="werkint_settings_array_node_remove")
     * @Rest\View()
     */
    public function arrayNodeRemoveAction(Request $req)
    {
        $sid = $req->request->get('sid');
        $setting = $this->serviceSettingsRepo()->findRow($sid);
        $this->getDoctrine()->getManager()->remove($setting);
        $this->getDoctrine()->getManager()->flush();
        return ['sid' => $sid];
    }
}
