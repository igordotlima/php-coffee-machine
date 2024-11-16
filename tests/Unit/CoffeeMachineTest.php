<?php

namespace Tests\Unit;

use CoffeeMachine\CoffeeMachine;
use CoffeeMachine\Parts\Buttons\Consumables\CoffeeButton;
use CoffeeMachine\Parts\Buttons\Contents\MilkContentButton;
use CoffeeMachine\Parts\Buttons\Contents\SugarContentButton;
use CoffeeMachine\Parts\Buttons\RefundButton;
use CoffeeMachine\Parts\Consumables\Coffee;
use CoffeeMachine\Parts\Contents\Milk;
use CoffeeMachine\Parts\Contents\Sugar;
use CoffeeMachine\Parts\Slots\BanknotePaymentSlot;
use CoffeeMachine\Parts\Slots\CoinPaymentSlot;
use CoffeeMachine\Support\Currency\Coin;
use PHPUnit\Framework\TestCase;

class CoffeeMachineTest extends TestCase
{

    public function test_can_get_coin_slots()
    {
        ////
        // 1. Arrange
        ////

        $slot = new CoinPaymentSlot();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addSlot($slot);

        ////
        // 3. Assert
        ////

        $this->assertContains($slot, $machine->getSlots());
    }

    public function test_can_get_banknote_slots()
    {
        ////
        // 1. Arrange
        ////

        $slot = new BanknotePaymentSlot();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addSlot($slot);

        ////
        // 3. Assert
        ////

        $this->assertContains($slot, $machine->getSlots());
    }

    public function test_can_get_captured_funds()
    {
        ////
        // 1. Arrange
        ////

        $consumable = new Coffee();

        $slot = new CoinPaymentSlot();
        $coin = new Coin($consumable->getPrice());

        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addSlot($slot);
        $machine->addButton(new CoffeeButton());
        $machine->addConsumableItem($consumable);

        $machine->addFunds($coin);

        $machine->pressButton('Coffee');

        ////
        // 3. Assert
        ////

        $this->assertEquals($machine->getCapturedFunds(), $consumable->getPrice());
    }

    public function test_can_set_selected_consumable()
    {
        ////
        // 1. Arrange
        ////

        $consumable = new Coffee();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addConsumableItem($consumable);
        $machine->setSelectedConsumable($consumable);

        ////
        // 3. Assert
        ////

        $this->assertEquals($consumable, $machine->getSelectedConsumable());
    }

    public function test_can_get_consumables()
    {
        ////
        // 1. Arrange
        ////

        $consumable = new Coffee();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addConsumableItem($consumable);

        ////
        // 3. Assert
        ////

        $this->assertNotEmpty($machine->getConsumables());
    }

    public function test_can_remove_consumable_item()
    {
        ////
        // 1. Arrange
        ////

        $consumable = new Coffee();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addConsumableItem($consumable);
        $machine->removeConsumableItem($consumable);

        ////
        // 3. Assert
        ////

        $this->assertNotContains($consumable, $machine->getConsumables());
    }

    public function test_can_add_content_item()
    {
        ////
        // 1. Arrange
        ////

        $content = new Sugar();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addContentItem($content);

        ////
        // 3. Assert
        ////

        $this->assertContains($content, $machine->getContents());
    }

    public function test_can_remove_content_item()
    {
        ////
        // 1. Arrange
        ////

        $content = new Sugar();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addContentItem($content);
        $machine->removeContentItem($content);

        ////
        // 3. Assert
        ////

        $this->assertNotContains($content, $machine->getContents());
    }

    public function test_can_get_buttons()
    {
        ////
        // 1. Arrange
        ////

        $button = new RefundButton();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addButton($button);

        ////
        // 3. Assert
        ////

        $this->assertContains($button, $machine->getButtons());
    }

    public function test_can_press_button()
    {
        ////
        // 1. Arrange
        ////

        $button = new RefundButton();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addButton($button);
        $machine->pressButton('Refund');

        ////
        // 3. Assert
        ////

        $this->assertContains($button, $machine->getButtons());
        $this->expectOutputString(PHP_EOL . '> Amount refunded: 0' . PHP_EOL);
    }

    public function test_can_add_consumable_item()
    {
        ////
        // 1. Arrange
        ////

        $consumable = new Coffee();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addConsumableItem($consumable);

        ////
        // 3. Assert
        ////

        $this->assertContains($consumable, $machine->getConsumables());
    }

    public function test_can_get_pending_funds()
    {
        ////
        // 1. Arrange
        ////

        $slot = new CoinPaymentSlot();
        $currency = new Coin(5);

        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addSlot($slot);
        $machine->addFunds($currency);

        ////
        // 3. Assert
        ////

        $this->assertEquals($currency->getValue(), $machine->getPendingFunds());
    }

    public function test_can_get_selected_consumable()
    {
        ////
        // 1. Arrange
        ////

        $consumable = new Coffee();

        $slot = new CoinPaymentSlot();
        $coin = new Coin($consumable->getPrice());

        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addSlot($slot);
        $machine->addButton(new CoffeeButton());
        $machine->addConsumableItem($consumable);

        $machine->addFunds($coin);

        $machine->pressButton('Coffee');

        ////
        // 3. Assert
        ////

        $this->assertEquals($consumable, $machine->getSelectedConsumable());
    }

    public function test_can_get_contents()
    {
        ////
        // 1. Arrange
        ////

        $content = new Sugar();
        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addContentItem($content);

        ////
        // 3. Assert
        ////

        $this->assertNotEmpty($machine->getContents());
    }

    public function test_can_update_content_level()
    {
        ////
        // 1. Arrange
        ////

        $consumable = new Coffee();

        $milkContent = new Milk();
        $sugarContent = new Sugar();

        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addContentItem($sugarContent);
        $machine->addContentItem($milkContent);

        $machine->addConsumableItem($consumable);

        $machine->addButton(new MilkContentButton());
        $machine->addButton(new SugarContentButton());

        $machine->addButton(new CoffeeButton());

        // First press the button without funds to select the consumable.
        $machine->pressButton('Coffee');

        // Then press the content button to update.
        $machine->pressButton('Milk');
        $machine->pressButton('Sugar');

        ////
        // 3. Assert
        ////

        $this->assertNotEmpty($machine->getContents());
        $this->assertNotEmpty($machine->getSelectedConsumable());

        $selectedConsumable = $machine->getSelectedConsumable();
        $selectedConsumableContents = $selectedConsumable->getContents();

        // Assert that we have 2 contents.
        $this->assertCount(2, $selectedConsumableContents);

        // Assert the contents match to what we added before.
        $this->assertEquals(get_class($selectedConsumableContents[0]), get_class($milkContent));
        $this->assertEquals(get_class($selectedConsumableContents[1]), get_class($sugarContent));
    }

    public function test_cannot_update_past_maximum_level()
    {
        ////
        // 1. Arrange
        ////

        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addContentItem(new Milk());
        $machine->addContentItem(new Sugar());

        $machine->addConsumableItem(new Coffee());

        $machine->addButton(new MilkContentButton());
        $machine->addButton(new SugarContentButton());

        $machine->addButton(new CoffeeButton());

        // First press the button without funds to select the consumable.
        $machine->pressButton('Coffee');

        // Then press the content button to update.
        for ($i = 0; $i < CoffeeMachine::MAX_CONSUMABLE_CONTENTS; $i++) {
            $machine->pressButton('Milk');
        }

        // Get and save the consumable contents before proceeding.
        $oldConsumableContents = $machine
            ->getSelectedConsumable()
            ->getContents();

        // Press one more time to go to zero.
        $machine->pressButton('Milk');

        // Get and save the consumable after proceeding.
        $newConsumable = $machine->getSelectedConsumable();

        ////
        // 3. Assert
        ////

        // Use the copy to validate the old data.
        $this->assertCount(CoffeeMachine::MAX_CONSUMABLE_CONTENTS, $oldConsumableContents);

        // Use this assertion to validate the difference.
        $this->assertEmpty($newConsumable->getContents());
    }

    public function test_can_display_pending_funds()
    {
        ////
        // 1. Arrange
        ////

        $slot = new CoinPaymentSlot();
        $coin = new Coin(1);

        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addSlot($slot);
        $machine->addFunds($coin);

        $machine->displayPendingFunds();

        ////
        // 3. Assert
        ////

        $this->assertEquals($coin->getValue(), $machine->getPendingFunds());
        $this->assertStringContainsString("Pending funds: {$coin->getValue()}", $this->getActualOutput());
    }

    public function test_can_display_captured_funds()
    {
        ////
        // 1. Arrange
        ////

        $slot = new CoinPaymentSlot();
        $coin = new Coin((new Coffee())->getPrice());

        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addSlot($slot);
        $machine->addFunds($coin);

        $machine->addButton(new CoffeeButton());
        $machine->addConsumableItem(new Coffee());

        $machine->pressButton('Coffee');

        $machine->displayCapturedFunds();

        ////
        // 3. Assert
        ////

        $this->assertEquals($coin->getValue(), $machine->getCapturedFunds());
        $this->assertStringContainsString("Captured funds: {$coin->getValue()}", $this->getActualOutput());
    }

    public function test_can_request_full_refund()
    {
        ////
        // 1. Arrange
        ////

        $slot = new CoinPaymentSlot();
        $coin = new Coin((new Coffee())->getPrice());

        $machine = new CoffeeMachine();

        ////
        // 2. Act
        ////

        $machine->addSlot($slot);
        $machine->addFunds($coin);

        $machine->addButton(new RefundButton());

        // Save the fund value before requesting a refund.
        $fundsBeforeRefund = $machine->getPendingFunds();

        $machine->pressButton('Refund');

        ////
        // 3. Assert
        ////

        // First check that the available funds were equal to the currency inserted.
        $this->assertEquals($coin->getValue(), $fundsBeforeRefund);

        // After make sure the amount was refunded.
        $this->assertEquals(0, $machine->getPendingFunds());
        $this->assertStringContainsString("Amount refunded: {$coin->getValue()}", $this->getActualOutput());
    }
}
