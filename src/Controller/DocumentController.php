<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Form\SearchFormType;
use App\Repository\DocumentRepository;
use App\Service\FileUploadServiceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="document_index", methods={"GET"})
     * @param DocumentRepository $documentRepository
     * @return Response
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        $searchForm = $this->createForm(SearchFormType::class);
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findByIsDeleted(FALSE),
            'search' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/new", name="document_new", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploadServiceManager $fileUploader
     * @return Response
     * @throws \Exception
     */
    public function new(Request $request, FileUploadServiceManager $fileUploader): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $searchForm = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $docObj = $form['documentName']->getData();
            $em->beginTransaction();
            try {
                if ($docObj instanceof UploadedFile) {
                    $uploadPath = $this->getParameter('doc_upload_path') . $form['folder']->getData()->getFolderName();
                    $uploadedFileName = $fileUploader->upload($docObj, $uploadPath);
                    $document->setDocumentName($uploadedFileName);
                    $document->setMimeType($docObj->getClientMimeType());
                    $em->persist($document);
                    $em->flush();
                    $em->commit();
                    return $this->redirectToRoute('document_index');
                }
            } catch (\Exception $e) {
                $em->rollback();
                echo 'An error occured : ' . $e->getMessage();
            }
        }
        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'search' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_show", methods={"GET"})
     */
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    /**
     * @Route("/search", name="document_search", methods={"POST"})
     */
    public function search(Request $request, DocumentRepository $documentRepository): Response
    {
        $searchForm = $this->createForm(SearchFormType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchParam = $searchForm->getData();
            $em = $this->getDoctrine()->getManager();
            $docs = $em->getRepository(Document::class)->findDocuments($searchParam);
            return $this->render('document/index.html.twig', [
                'documents' => $docs,
                'search' => $searchForm->createView(),
            ]);
        }
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findByIsDeleted(FALSE),
            'search' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_index');
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $document->setIsDeleted(TRUE);
            $entityManager->flush();
        }
        return $this->redirectToRoute('document_index');
    }
}
