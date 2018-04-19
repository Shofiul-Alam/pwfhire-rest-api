<?php
namespace AppBundle\Form;

use BackendBundle\Entity\Base\AImage;
use BackendBundle\Entity\EmployeeSkillCompetencyDocument;
use BackendBundle\Entity\EmployeeSkillDocument;
use Doctrine\DBAL\Types\ObjectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Tests\Form\Type\EntityTypePerformanceTest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Tests\Extension\Core\Type\NumberTypeTest;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class EmployeeSkillEompetencyDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('issueDate', TextType::class)
                ->add('expiryDate', TextType::class)
                ->add('employee', EntityType::class, array('class' => 'BackendBundle:Employee'))
                ->add('skillCompetencyList', EntityType::class, array('class' => 'BackendBundle:SkillCompetencyList'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => EmployeeSkillCompetencyDocument::class,
            'csrf_protection' => false
        ));
    }

    public function getBlockPrefix()
    {
        return '';
    }
}