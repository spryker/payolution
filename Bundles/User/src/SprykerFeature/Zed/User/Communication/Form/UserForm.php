<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\User\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;

class UserForm extends AbstractForm
{

    const USERNAME = 'username';
    const GROUP = 'group';
    const FIRST_NAME = 'first_name';
    const LAST_NAME = 'last_name';
    const PASSWORD = 'password';
    const STATUS = 'status';

    /**
     * @var array
     */
    protected $allAclGroups;

    /**
     * Prepares form
     *
     * @return self
     */
    protected function buildFormFields()
    {
        $this->addUsername()
            ->addRepeatedUserPassword()
            ->addFirstName()
            ->addLastName()
            ->addGroupSelect();

        return $this;
    }

    /**
     * @return self
     */
    protected function addUsername()
    {
        $this->addText(
            self::USERNAME,
            [
                'label' => 'Username',
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @return self
     */
    protected function addRepeatedUserPassword()
    {
        $this->addRepeated(
            self::PASSWORD,
            [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
                'invalid_message' => 'The password fields must match.',
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'required' => true,
                'type' => 'password',
            ]
        );

        return $this;
    }

    /**
     * @return self
     */
    protected function addFirstName()
    {
        $this->addText(
            self::FIRST_NAME,
            [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @return self
     */
    protected function addLastName()
    {
        $this->addText(
            self::LAST_NAME,
            [
                'constraints' => [
                    $this->getConstraints()->createConstraintNotBlank(),
                ],
            ]
        );

        return $this;
    }

    /**
     * @return self
     */
    protected function addGroupSelect()
    {
        $this->addSelect(
            self::GROUP,
            [
                'constraints' => [
                    $this->getConstraints()->createConstraintChoice([
                        'choices' => array_keys($this->getGroupChoices()),
                        'multiple' => true,
                        'min' => 1,
                    ]),
                ],
                'label' => 'Assigned groups',
                'multiple' => true,
                'expanded' => true,
                'choices' => $this->getGroupChoices(),
            ]
        );

        return $this;
    }

    /**
     * @return self
     */
    public function addUserStatus()
    {
        $this->addSelect(
            self::STATUS,
            [
                'choices' => $this->getStatusSelectChoices(),
            ]
        );

        return $this;
    }

    /**
     * @return array
     */
    protected function getGroupChoices()
    {
        if ($this->allAclGroups === null) {
            $groupsTransfer = $this->getLocator()->acl()->facade()->getAllGroups();

            foreach ($groupsTransfer->getGroups() as $groupTransfer) {
                $this->allAclGroups[$groupTransfer->getIdAclGroup()] =
                    $this->formatGroupName($groupTransfer->getName());
            }
        }

        return $this->allAclGroups;
    }

    /**
     * @param string $groupName
     *
     * @return string
     */
    protected function formatGroupName($groupName)
    {
        return str_replace('_', ' ', ucfirst($groupName));
    }

    /**
     * @return array
     */
    protected function getStatusSelectChoices()
    {
        return array_combine(
            SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS),
            SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS)
        );
    }

    /**
     * Set the values for fields
     *
     * @return array
     */
    protected function populateFormFields()
    {
        return [];
    }

}
