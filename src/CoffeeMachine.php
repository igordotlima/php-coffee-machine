<?php

namespace CoffeeMachine;

use CoffeeMachine\Contracts\Buttons\MachineButton;
use CoffeeMachine\Contracts\Buttons\MachineContentButton;
use CoffeeMachine\Contracts\Consumables\ConsumableItem;
use CoffeeMachine\Contracts\Consumables\ContentItem;
use CoffeeMachine\Contracts\Payment\PaymentCurrency;
use CoffeeMachine\Contracts\Payment\PaymentSlot;
use CoffeeMachine\Exceptions\Consumables\InsufficientStockException;
use CoffeeMachine\Exceptions\Payments\InsufficientFundsException;
use CoffeeMachine\Types\ButtonType;
use CoffeeMachine\Utils\ConsoleUtils;
use InvalidArgumentException;
use LogicException;
use Throwable;

/**
 * This class is responsible for evaluating my attempt
 * at building a coffee machine in PHP without a framework.
 *
 * @author Igor Lima <igordotlima (at) gmail.com>
 */
final class CoffeeMachine
{
    /**
     * Define the maximum possible contents for a consumable.
     */
    public const MAX_CONSUMABLE_CONTENTS = 4;

    /**
     * The selected consumable.
     */
    private ?ConsumableItem $selectedConsumable = null;

    /**
     * The installed payment slots.
     *
     * @var array<PaymentSlot>
     */
    private array $slots = [];

    /**
     * The available consumables inside the machine.
     *
     * @var array<class-string<ConsumableItem>, ConsumableItem[]>
     */
    private array $consumables = [];

    /**
     * The contents available to mix with the consumables.
     *
     * @var ContentItem[]
     */
    private array $contents = [];

    /**
     * The installed buttons.
     *
     * @var array<MachineButton>
     */
    private array $buttons = [];

    /**
     * Gets the installed buttons.
     *
     * @return MachineButton[]
     */
    public function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * Gets a button by the specified name.
     *
     * @param string $name  The name to search for.
     *
     * @return null|MachineButton
     */
    private function getButtonByName(string $name): ?MachineButton
    {
        foreach ($this->getButtons() as $button) {

            // Make both names lowercase to make the search case-insensitive.
            if (strtolower($button->getName()) === strtolower($name)) {
                return $button;
            }
        }

        return null;
    }

    /**
     * Install a button in the machine.
     *
     * @param MachineButton $button
     *
     * @return void
     */
    public function addButton(MachineButton $button): void
    {
        $this->buttons[$button->getId()] = $button;
    }

    /**
     * Gets the installed payment slots.
     *
     * @return PaymentSlot[]
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

    /**
     * Install a payment slot in the machine.
     *
     * @param PaymentSlot $slot
     *
     * @return void
     */
    public function addSlot(PaymentSlot $slot): void
    {
        $this->slots[] = $slot;
    }

    /**
     * Get the appropriate slot to handle a currency.
     *
     * @param PaymentCurrency $currency
     * @return PaymentSlot|null     The payment slot instance or null if none found.
     */
    public function findPaymentSlot(PaymentCurrency $currency): ?PaymentSlot
    {
        foreach ($this->getSlots() as $slot) {
            if ($slot->acceptsCurrency($currency)) {
                return $slot;
            }
        }

        return null;
    }

    /**
     * Gets the available consumable items.
     *
     * @return ConsumableItem[]
     */
    public function getConsumables(): array
    {
        return $this->consumables;
    }

    /**
     * Determine the consumable exists in the machine.
     *
     * @param class-string<ConsumableItem> $consumable
     *
     * @return bool
     */
    public function hasConsumable(string $consumable): bool
    {
        $consumables = array_filter($this->getConsumables(), function (ConsumableItem $item) use ($consumable) {
            return get_class($item) === $consumable;
        });

        return count($consumables) > 0;
    }

    /**
     * Places a consumable inside the machine.
     *
     * @param ConsumableItem $item
     *
     * @return void
     */
    public function addConsumableItem(ConsumableItem $item): void
    {
        $this->consumables[get_class($item)] = $item;
    }

    /**
     * Takes a consumable out of the machine.
     *
     * @param ConsumableItem $item
     *
     * @return void
     */
    public function removeConsumableItem(ConsumableItem $item): void
    {
        $this->consumables = array_filter(
            array: $this->getConsumables(),
            callback: function (ConsumableItem $value) use ($item) {
                return $item !== $value;
            }
        );
    }

    /**
     * Gets the selected consumable item.
     *
     * @return ConsumableItem|null
     */
    public function getSelectedConsumable(): ?ConsumableItem
    {
        return $this->selectedConsumable;
    }

    /**
     * Set the selected consumable item.
     *
     * @param ConsumableItem $consumable
     */
    public function setSelectedConsumable(ConsumableItem $consumable): void
    {
        $this->selectedConsumable = $consumable;
    }

    /**
     * Gets the available contents.
     *
     * @return ContentItem[]
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * Determine the content exists in the machine.
     *
     * @param class-string<ContentItem> $content
     *
     * @return bool
     */
    public function hasContent(string $content): bool
    {
        $contents = array_filter($this->getContents(), function (ContentItem $item) use ($content) {
            return get_class($item) === $content;
        });

        return count($contents) > 0;
    }

    /**
     * Places a consumable inside the machine.
     *
     * @param ContentItem $item
     *
     * @return void
     */
    public function addContentItem(ContentItem $item): void
    {
        $this->contents[] = $item;
    }

    /**
     * Takes a consumable out of the machine.
     *
     * @param ContentItem $item
     *
     * @return void
     */
    public function removeContentItem(ContentItem $item): void
    {
        $this->contents = array_filter(
            array: $this->getContents(),
            callback: function (ContentItem $value) use ($item) {
                return $item !== $value;
            }
        );
    }

    ////
    // Actions
    ////

    /**
     * Attempts to buy a consumable item.
     *
     * @throws InsufficientFundsException   If the client does not have sufficient funds.
     * @throws InsufficientStockException   If the consumable is out of stock.
     */
    private function purchaseConsumable(): void
    {
        // Get the selected consumable.
        $consumable = $this->getSelectedConsumable();

        if (is_null($consumable)) {
            throw new InvalidArgumentException('No consumable item selected.');
        }

        // Move the funds into the vault and refund the remaining.
        $this->capturePendingFunds();

        // Reduce consumable stock quantity.
        $consumable->consume();

        // Reduce content stock quantity.
        foreach ($consumable->getContents() as $content) {
            $content->consume();

            // Feedback
            ConsoleUtils::writeLine("Adding '{$content->getName()}' to consumable item.");
        }

        // Give some feedback.
        ConsoleUtils::writeLine($consumable->getName() . ' purchased successfully.');
    }

    /**
     * Attempts to select a consumable on the machine.
     *
     * @param class-string<ConsumableItem> $consumable The selected consumable item class name.
     *
     * @return void
     * @throws InvalidArgumentException     If the consumable does not exist.
     */
    private function selectConsumable(string $consumable): void
    {
        // Validate the class argument.
        if (!is_subclass_of($consumable, ConsumableItem::class)) {
            throw new InvalidArgumentException(
                message: 'Invalid class string.'
            );
        }

        if (!$this->hasConsumable($consumable)) {
            throw new InvalidArgumentException(
                message: sprintf("The consumable '%s' does not exist.", $consumable)
            );
        }

        // Get all the consumables related to the specified class.
        $consumables = $this->getConsumables();

        // Go through all available consumables and select the first one available.
        foreach ($consumables as $item) {
            if (!$item->isOutOfStock()) {
                $this->setSelectedConsumable($item);

                break;
            }
        }

        // Nothing available at the moment.
        if (is_null($this->getSelectedConsumable())) {
            throw new InvalidArgumentException(
                message: sprintf("No consumable stock available for '%s'.", $consumable)
            );
        }
    }

    /**
     * Updates the consumable content level by
     * adding or removing instances to the consumable.
     *
     * @param class-string<ContentItem> $content
     *
     * @return void
     */
    private function updateConsumableContentLevel(string $content): void
    {
        // Validate the class argument.
        if (!is_subclass_of($content, ContentItem::class)) {
            throw new InvalidArgumentException(
                message: 'Invalid class string.'
            );
        }

        // Check if exists in the machine.
        if (!$this->hasContent($content)) {
            throw new InvalidArgumentException(
                message: sprintf("The content '%s' does not exist.", $content)
            );
        }

        // Nothing selected.
        if (is_null($this->getSelectedConsumable())) {
            throw new LogicException(
                message: 'No consumable selected.'
            );
        }

        $consumable = $this->getSelectedConsumable();

        // Get all matching content instances.
        $contents = array_filter(
            array: $consumable->getContents(),
            callback: function (ContentItem $value) use ($content) {
                return get_class($value) === $content;
            }
        );

        // Reset the content level and exit since the level is between 0 and 4.
        if (count($contents) >= self::MAX_CONSUMABLE_CONTENTS) {
            $consumable->clearContents();

            return;
        }

        $consumable->addContentItem(new $content);
    }

    ////
    // Handlers
    ////

    /**
     * Handles a button press.
     *
     * @param MachineButton $button
     *
     * @return void
     * @throws InsufficientFundsException If the client does not have sufficient funds.
     * @throws InsufficientStockException If the consumable is out of stock.
     */
    private function handleButtonPress(MachineButton $button): void
    {
        switch ($button->getType()) {
            case ButtonType::TYPE_CONSUMABLE:
            {
                // Find a matching consumable in stock.
                $this->selectConsumable($button->getConsumable());

                // Only attempt to purchase an existing consumable.
                if ($this->getSelectedConsumable()) {
                    $this->purchaseConsumable();
                }

                break;
            }

            case ButtonType::TYPE_CONTENT:
            {
                if (!$button instanceof MachineContentButton) {
                    throw new InvalidArgumentException(
                        message: sprintf('Button must implement %s.', MachineContentButton::class)
                    );
                }

                $this->updateConsumableContentLevel($button->getContent());

                break;
            }

            case ButtonType::TYPE_REFUND:
            {
                $this->shouldRefundPendingFunds();

                break;
            }
        }
    }

    ////
    // Methods
    ////

    /**
     * Refunds the pending funds in all slots.
     *
     * @return void
     */
    private function shouldRefundPendingFunds(): void
    {
        // Set the initial counter.
        $amount = 0;

        // Go through all the slots and refund the pending amounts.
        foreach ($this->getSlots() as $slot) {

            if ($slot->getPendingAmount() > 0) {
                $amount += $slot->refund();
            }
        }

        ConsoleUtils::writeLine('Amount refunded: ' . $amount);
    }

    /**
     * Capture the pending funds for the transaction.
     *
     * @throws InsufficientFundsException
     */
    private function capturePendingFunds(): void
    {
        $consumable = $this->getSelectedConsumable();

        if (is_null($consumable)) {
            throw new LogicException('No consumable item selected.');
        }

        if ($this->getPendingFunds() < $consumable->getPrice()) {
            throw new InsufficientFundsException($consumable->getPrice());
        }

        // Go through all the available slots and capture the funds.
        foreach ($this->getSlots() as $slot) {

            // Set the amount to be captured.
            $captureAmount = $consumable->getPrice();

            if ($captureAmount >= $slot->getPendingAmount()) {
                $slot->capture($captureAmount);

                break;
            }
        }

        $this->shouldRefundPendingFunds();
    }


    /**
     * Gets the total of pending funds.
     *
     * @return int
     */
    public function getPendingFunds(): int
    {
        $amount = 0;

        foreach ($this->getSlots() as $slot) {
            $amount += $slot->getPendingAmount();
        }

        return $amount;
    }

    /**
     * Gets the total of captured funds.
     */
    public function getCapturedFunds(): int
    {
        $amount = 0;

        foreach ($this->getSlots() as $slot) {
            $amount += $slot->getCapturedAmount();
        }

        return $amount;
    }

    /**
     * Display the current inserted funds.
     *
     * @return void
     */
    public function displayPendingFunds(): void
    {
        ConsoleUtils::writeLine(
            string: sprintf('Pending funds: %d', $this->getPendingFunds())
        );
    }

    /**
     * Display the current captured funds.
     *
     * @return void
     */
    public function displayCapturedFunds(): void
    {
        ConsoleUtils::writeLine(
            string: sprintf('Captured funds: %d', $this->getCapturedFunds())
        );
    }


    /**
     * Add funds to the machine.
     *
     * @param PaymentCurrency $currency
     *
     * @return void
     */
    public function addFunds(PaymentCurrency $currency): void
    {
        $slot = $this->findPaymentSlot($currency);

        if (is_null($slot)) {
            ConsoleUtils::writeLine('No compatible slots found.');
            return;
        }

        // Add currency to slot.
        $slot->insert($currency);

        // Visual feedback.
        ConsoleUtils::writeLine(
            string: sprintf(
                '%d %s added.',
                $currency->getValue(),
                strtolower($currency->getPluralName())
            ),
        );
    }

    /**
     * Presses a button in the machine.
     *
     * @param string|MachineButton $button
     */
    public function pressButton(string|MachineButton $button): void
    {
        if (is_string($button)) {
            $button = $this->getButtonByName($button);
        }

        if (!in_array($button, $this->buttons)) {
            throw new InvalidArgumentException('You attempted to press button that does not exist.');
        }

        try {
            $this->handleButtonPress($button);

        } catch (Throwable $e) {
            ConsoleUtils::writeException($e);
        }
    }
}