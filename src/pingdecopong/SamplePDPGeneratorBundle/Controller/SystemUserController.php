<?php

namespace pingdecopong\SamplePDPGeneratorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use pingdecopong\PagerBundle\Pager\Pager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use pingdecopong\SamplePDPGeneratorBundle\Entity\SystemUser;
use pingdecopong\SamplePDPGeneratorBundle\Form\SystemUserType;
use pingdecopong\SamplePDPGeneratorBundle\Form\SystemUserSearchType;

/**
 * SystemUser controller.
 *
 * @Route("/systemuser")
 */
class SystemUserController extends Controller
{

    /**
     * Lists all SystemUser entities.
     *
     * @Route("/", name="systemuser")
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
            ->addColumn('email', array(
                'label' => 'email',
                'sort_enable' => true,
                'db_column_name' => 'email',
            ))
        ;

        $form = $formFactory->createNamedBuilder('f', 'form', null, array('csrf_protection' => false))
            ->add($pager->getFormBuilder())
            ->add('search', new SystemUserSearchType())
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
            ->getRepository('pingdecopongSamplePDPGeneratorBundle:SystemUser')
            ->createQueryBuilder('u');

        //検索
        $data = $form->getData();
        //id
        $searchId = $data['search']->getId();
        if(isset($searchId) && $form['search']['id']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.id LIKE :Id')
                ->setParameter('Id', '%'.$searchId.'%');
        }
        //email
        $searchEmail = $data['search']->getEmail();
        if(isset($searchEmail) && $form['search']['email']->isValid())
        {
            $queryBuilder = $queryBuilder->andWhere('u.email LIKE :Email')
                ->setParameter('Email', '%'.$searchEmail.'%');
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

        return array(
            'form' => $formView,
            'pager' => $pager->createView(),
            'entities' => $entities,
        );
    }

    /**
     * SystemUser新規作成
     *
     * @Route("/new", name="systemuser_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction()
    {
        $entity = new SystemUser();
        $form   = $this->createForm(new SystemUserType(), $entity);

        $request = $this->getRequest();
        $formModel = new SystemUser();
        $formType = new SystemUserType();

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
                        return $this->redirect($this->generateUrl('systemuser_show', array('id' => $formModel->getId())));                    }
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
        );
    }

    /**
     * SystemUser詳細
     *
     * @Route("/{id}", name="systemuser_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('pingdecopongSamplePDPGeneratorBundle:SystemUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SystemUser entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Displays a form to edit an existing SystemUser entity.
     *
     * @Route("/edit/{id}", name="systemuser_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction($id)
    {
        $request = $this->getRequest();
        $formType = new SystemUserType();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('pingdecopongSamplePDPGeneratorBundle:SystemUser')->find($id);
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
                    return $this->redirect($this->generateUrl('systemuser_show', array('id' => $entity->getId())));                    }
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
        );
    }
    /**
     * Deletes a SystemUser entity.
     *
     * @Route("/delete/{id}", name="systemuser_delete")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function deleteAction($id)
    {
        $request = $this->getRequest();
        $form = $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('pingdecopongSamplePDPGeneratorBundle:SystemUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SystemUser entity.');
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
                return $this->redirect($this->generateUrl('systemuser'));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }


}
