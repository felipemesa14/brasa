<?php

namespace Brasa\TransporteBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DespachosAgregarGuiaController extends Controller
{

    public function listaAction($codigoDespacho) {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arGuias = $em->getRepository('BrasaTransporteBundle:TteGuias')->findBy(array('codigoDespachoFk' => NULL));
        $arDespacho = new \Brasa\TransporteBundle\Entity\TteDespachos();
        $arDespacho = $em->getRepository('BrasaTransporteBundle:TteDespachos')->find($codigoDespacho);
        if ($request->getMethod() == 'POST') {
            $arrControles = $request->request->All();
            switch ($request->request->get('OpSubmit')) {
                case "OpBuscar";
                    if($request->request->get('TxtDescripcionItem') != "")
                        $arItemes = $em->getRepository('BrasaInventarioBundle:InvItem')->BuscarDescripcionItem($request->request->get('TxtDescripcionItem'));

                    if($request->request->get('TxtCodigoItem') != "")
                        $arItemes = $em->getRepository('BrasaInventarioBundle:InvItem')->find($request->request->get('TxtCodigoItem'));
                    break;
                case "OpAgregar";
                    $arrSeleccionados = $request->request->get('ChkSeleccionar');
                    if (count($arrSeleccionados) > 0) {
                        $intUnidades = $arDespacho->getCtUnidades();
                        $intPesoReal = $arDespacho->getCtPesoReal();
                        $intPesoVolumen = $arDespacho->getCtPesoVolumen();
                        foreach ($arrSeleccionados AS $codigoGuia) {
                            $arGuia = new \Brasa\TransporteBundle\Entity\TteGuias();
                            $arGuia = $em->getRepository('BrasaTransporteBundle:TteGuias')->find($codigoGuia);
                            $arGuia->setEstadoDespachada(1);
                            $arGuia->setDespachoRel($arDespacho);
                            $em->persist($arGuia);
                            $em->flush();
                            $intUnidades = $intUnidades + $arGuia->getCtUnidades();
                            $intPesoReal = $intPesoReal + $arGuia->getCtPesoReal();
                            $intPesoVolumen = $intPesoVolumen + $arGuia->getCtPesoVolumen();
                        }

                        $arDespacho->setCtUnidades($intUnidades);
                        $arDespacho->setCtPesoReal($intPesoReal);
                        $arDespacho->setCtPesoVolumen($intPesoVolumen);
                        $em->persist($arDespacho);
                        $em->flush();
                    }
                    echo "<script languaje='javascript' type='text/javascript'>window.close();window.opener.location.reload();</script>";
                    break;
            }
        }
        return $this->render('BrasaTransporteBundle:Despachos:agregarGuia.html.twig', array('arGuias' => $arGuias, 'arDespacho' => $arDespacho));
    }
}
