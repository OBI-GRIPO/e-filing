<?php
declare(strict_types=1);

namespace App\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Symfony\Reply\HttpResponse;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\RenderTemplate;
use Payum\Core\Security\TokenInterface;
use App\Model\Payment;
use App\Request\ObtainMissingDetailsRequest;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class ObtainMissingDetailsAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var string
     */
    protected $templateName;

    public function __construct(FormFactoryInterface $formFactory, string $templateName)
    {
        $this->formFactory = $formFactory;
        $this->templateName = $templateName;
    }

    /**
     * {@inheritdoc}
     *
     * @param ObtainMissingDetailsRequest $request
     */
    public function execute($request) : void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $form = $this->createPaymentForm($request->getPayment(), $request->getToken());
        // we have everything we need if there is not fields in the form.

        if (count($form) < 1) {
            return;
        }

        $this->gateway->execute($httpRequest = new GetHttpRequest());
        if ('POST' == $httpRequest->method) {
            $form->submit($httpRequest->request);
            if ($form->isSubmitted() && $form->isValid()) {
                return;
            }
        }

        $renderTemplate = new RenderTemplate($this->templateName, [
            'model' => $request->getPayment(),
            'form' => $form->createView(),
        ]);
        $this->gateway->execute($renderTemplate);

        throw new HttpResponse(new Response($renderTemplate->getResult(), 200, [
            'X-Status-Code' => 200,
            'Cache-Control' => 'no-store, no-cache, max-age=0, post-check=0, pre-check=0',
            'Pragma' => 'no-cache',
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request) : bool
    {
        return $request instanceof ObtainMissingDetailsRequest;
    }

    protected function createPaymentForm(Payment $payment, TokenInterface $token = null) : FormInterface
    {
        return $this->createPaymentFormBuilder($payment, $token)->getForm();
    }

    protected function createPaymentFormBuilder(Payment $payment, TokenInterface $token = null) : FormBuilderInterface
    {
        return $this->formFactory->createNamedBuilder('', FormType::class, $payment, [
            'method' => 'POST',
            'action' => $token ? $token->getTargetUrl() : null,
            'data_class' => Payment::class,
            'csrf_protection' => false,
            'attr' => ['class' => 'payum-obtain-missing-details'],
            'allow_extra_fields' => true,
        ]);
    }
}