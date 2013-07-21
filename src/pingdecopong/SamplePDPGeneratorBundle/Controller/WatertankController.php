<?php

namespace pingdecopong\SamplePDPGeneratorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use pingdecopong\PagerBundle\Pager\Pager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use pingdecopong\SamplePDPGeneratorBundle\Entity\Watertank;
use pingdecopong\SamplePDPGeneratorBundle\Form\WatertankType;
use pingdecopong\SamplePDPGeneratorBundle\Form\WatertankSearchType;

/**
 * Watertank controller.
 *
 * @Route("/watertank")
 */
class WatertankController extends Controller
{

    /**
     * Lists all Watertank entities.
     *
     * @Route("/", name="watertank")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $formFactory = $this->get('form.factory');
        $validator = $this->get('validator');
        $pager = new Pager($formFactory, $validator);

        //pager setting
        $pager
            ->addColumn('id', array(
                'label' => 'id',
                'sort_enable' => true,
                'db_column_name' => 'id',
            ))
            ->addColumn('Name', array(
                'label' => 'Name',
                'sort_enable' => true,
                'db_column_name' => 'Name',
            ))
            ->addColumn('SystemUserId', array(
                'label' => 'SystemUserId',
                'sort_enable' => true,
                'db_column_name' => 'SystemUserId',
            ))
            ->addColumn('IntegerData', array(
                'label' => 'IntegerData',
                'sort_enable' => true,
                'db_column_name' => 'IntegerData',
            ))
            ->addColumn('DefTest1', array(
                'label' => 'DefTest1',
                'sort_enable' => true,
                'db_column_name' => 'DefTest1',
            ))
        ;

        $form = $formFactory->createNamedBuilder('f', 'form', null, array('csrf_protection' => false))
            ->add($pager->getFormBuilder())
            ->add('search', new WatertankSearchType())
            ->getForm();
        $form->bind($request);

        //pager
        $formView = $form->createView();
        $pager->setAllFormView($formView);
        $pager->setPagerFormView($formView[$pager->getFormName()]);
        $pager->setLinkRouteName($request->get('_route'));

        if($request->isMethod('POST') && $form->isValid())
        {
            $queryAllData = $pager->getAllFormQueryStrings();
            $queryPagerData = $pager->getPagerFormQueryKeyStrings();
            $queryAllData[$queryPagerData['pageNo']] = 1;

            return $this->redirect($this->generateUrl($request->get('_route'), $queryAllData));
        }

        if(($request->isMethod('GET') && !$form->isValid()) || !$pager->isValid())
        {
            return $this->redirect($this->generateUrl($request->get('_route')));
        }

        //db
        $queryBuilder = $this->getDoctrine()
            ->getRepository('pingdecopongSamplePDPGeneratorBundle:Watertank')
            ->createQueryBuilder('u');

        //検索
        $data = $form->getData();
        //Name
        $searchName = $data['search']->getName();
        if(isset($searchName) && $form['search']['Name']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.Name LIKE :Name')
                ->setParameter('Name', '%'.$searchName.'%');
        }
        //SystemUserId
        $searchSystemuserid = $data['search']->getSystemuserid();
        if(isset($searchSystemuserid) && $form['search']['SystemUserId']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.SystemUserId = :Systemuserid')
                ->setParameter('Systemuserid', $searchSystemuserid);
        }
        //IntegerData
        $searchIntegerdata = $data['search']->getIntegerdata();
        if(isset($searchIntegerdata) && $form['search']['IntegerData']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.IntegerData = :Integerdata')
                ->setParameter('Integerdata', $searchIntegerdata);
        }
        //DefTest1
        $searchDeftest1 = $data['search']->getDeftest1();
        if(isset($searchDeftest1) && $form['search']['DefTest1']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.DefTest1 LIKE :Deftest1')
                ->setParameter('Deftest1', '%'.$searchDeftest1.'%');
        }

        //relation 検索
        //systemuser
        $searchSystemuser = $data['search']->getSystemuser();
        if(isset($searchSystemuser) && $form['search']['systemuser']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.systemuser = :Systemuser')
                ->setParameter('Systemuser', $searchSystemuser);
        }

        //全件数取得
        $queryBuilderCount = clone $queryBuilder;
        $queryBuilderCount = $queryBuilderCount->select('count(u.id)');
        $queryCount = $queryBuilderCount->getQuery();
        $allCount = $queryCount->getSingleScalarResult();
        $pager->setAllCount($allCount);

        //ソート
        $pageSortName = $pager->getSortName();
        $pageSortType = $pager->getSortType();
        if($pageSortName != null && $pageSortType != null)
        {
            switch($pageSortName)
            {
                default:
                    $sortColumn = $pager->getColumn($pageSortName);
                    $queryBuilder = $queryBuilder->orderBy('u.'.$sortColumn['db_column_name'], $pageSortType);
                    break;
            }
        }

        //ページング
        $pageSize = $pager->getPageSize();
        $pageNo = $pager->getPageNo();
        if($pager->getMaxPageNum() < $pageNo){
            return $this->redirect($request->get('_route'));
        }
        $queryBuilder = $queryBuilder->setFirstResult($pageSize*($pageNo-1))
            ->setMaxResults($pageSize);

        //クエリー実行
        $entities = $queryBuilder->getQuery()->getResult();

        //returnURL生成
        $returnUrlQueryDataArray = $pager->getAllFormQueryStrings();
        $returnUrlQueryString = urlencode(http_build_query($returnUrlQueryDataArray));

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'entities' => $entities,
            'returnUrlParam' => $returnUrlQueryString,
        );
    }

    /**
     * Watertank新規作成
     *
     * @Route("/new", name="watertank_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction()
    {

        $request = $this->getRequest();
        //returnUrlデコード
        $returnUrlQueryString = urldecode($request->get('ret'));

        $entity = new Watertank();
        $form   = $this->createForm(new WatertankType(), $entity);
        $formModel = new Watertank();
        $formType = new WatertankType();

        /* @var $builder \Symfony\Component\Form\FormBuilderInterface */
        $builder = $this->get('form.factory')->createBuilder($formType, $formModel);
        $form = $builder->getForm();

        if($request->isMethod('POST'))
        {
            $form->bind($request);

            //button_action
            $buttonAction = $request->request->get('_button_action');

            if($buttonAction == "confirm" || $buttonAction == "submit")
            {
                if($form->isValid())
                {
                    if($buttonAction == "confirm")
                    {
                        //確認画面
                        $builder->setAttribute('freeze', true);
                        $confirmForm = $builder->getForm();

                        return array(
                            'mode' => "confirm",
                            'entity' => $formModel,
                            'form' => $confirmForm->createView(),
                            'returnUrlParam' => $returnUrlQueryString,
                        );

                    }else if($buttonAction == "submit")
                    {
                        //登録実行
                        try{
                            $em = $this->getDoctrine()->getManager();
                            $em->persist($formModel);
                            $em->flush();

                        }catch (\Exception $e){
                            throw $e;
                        }
                        return $this->redirect($this->generateUrl('watertank_show', array('id' => $formModel->getId())));                    }
                }
            }else
            {
                //ドロップダウンなどのポストバック
                $builder->setAttribute('novalidation', true);
                $form = $builder->getForm();
            }
        }


        return array(
            'mode' => "input",
            'validate' => false,
            'entity' => $formModel,
            'form' => $form->createView(),
            'returnUrlParam' => $returnUrlQueryString,
        );
    }

    /**
     * Watertank詳細
     *
     * @Route("/{id}", name="watertank_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('pingdecopongSamplePDPGeneratorBundle:Watertank')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Watertank entity.');
        }

        //returnUrlデコード
        $returnUrlQueryString = urldecode($request->get('ret'));

        return array(
            'entity'      => $entity,
            'returnUrlParam' => $returnUrlQueryString,
        );
    }

    /**
     * Displays a form to edit an existing Watertank entity.
     *
     * @Route("/edit/{id}", name="watertank_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction($id)
    {

        $request = $this->getRequest();
        //returnUrlデコード
        $returnUrlQueryString = urldecode($request->get('ret'));
        $formType = new WatertankType();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('pingdecopongSamplePDPGeneratorBundle:Watertank')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SystemUser entity.');
        }

        /* @var $builder \Symfony\Component\Form\FormBuilderInterface */
        $builder = $this->get('form.factory')->createBuilder($formType, $entity);
        $form = $builder->getForm();

        if($request->isMethod('POST'))
        {
            $form->bind($request);

            //button_action
            $buttonAction = $request->request->get('_button_action');

            if($buttonAction == "confirm" || $buttonAction == "submit")
            {
                if($form->isValid())
                {
                    if($buttonAction == "confirm")
                    {
                        //確認画面
                        $builder->setAttribute('freeze', true);
                        $confirmForm = $builder->getForm();

                        return array(
                            'mode' => "confirm",
                            'entity' => $entity,
                            'form' => $confirmForm->createView(),
                            'returnUrlParam' => $returnUrlQueryString,
                        );

                    }else if($buttonAction == "submit")
                    {
                        //登録実行
                        try{
                            $em->persist($entity);
                            $em->flush();

                        }catch (\Exception $e){
                            throw $e;
                        }
                    return $this->redirect($this->generateUrl('watertank_show', array('id' => $entity->getId())));                    }
                }
            }else
            {
                //ドロップダウンなどのポストバック
                $builder->setAttribute('novalidation', true);
                $form = $builder->getForm();
            }
        }

        return array(
            'mode' => "input",
            'validate' => false,
            'entity' => $entity,
            'form' => $form->createView(),
            'returnUrlParam' => $returnUrlQueryString,
        );
    }
    /**
     * Deletes a Watertank entity.
     *
     * @Route("/delete/{id}", name="watertank_delete")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function deleteAction($id)
    {
        $request = $this->getRequest();
        //returnUrlデコード
        $returnUrlQueryString = urldecode($request->get('ret'));

        $form = $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('pingdecopongSamplePDPGeneratorBundle:Watertank')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Watertank entity.');
        }

        if($request->isMethod('POST'))
        {
            $form->bind($request);
            if ($form->isValid()) {

                try{
                    $em->remove($entity);
                    $em->flush();

                }catch (\Exception $e){
                    throw $e;
                }
                return $this->redirect($this->generateUrl('watertank'));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'returnUrlParam' => $returnUrlQueryString,
        );
    }


}
