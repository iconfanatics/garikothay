<x-filament-panels::page>
    <div class="space-y-12">
        <!-- Overview Group -->
        <div class="space-y-4">
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                Overview
            </h2>
            <x-filament-widgets::widgets
                :columns="$this->getColumns()"
                :data="$this->getWidgetData()"
                :widgets="$this->getOverviewWidgets()"
            />
        </div>

        <!-- Inventory Group -->
        <div class="space-y-4">
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                Inventory Analytics
            </h2>
            <x-filament-widgets::widgets
                :columns="$this->getColumns()"
                :data="$this->getWidgetData()"
                :widgets="$this->getInventoryWidgets()"
            />
        </div>

        <!-- Payment Group -->
        <div class="space-y-4">
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                Payment & Revenue
            </h2>
            <x-filament-widgets::widgets
                :columns="$this->getColumns()"
                :data="$this->getWidgetData()"
                :widgets="$this->getPaymentWidgets()"
            />
        </div>

        <!-- Marketing Group -->
        <div class="space-y-4">
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                Marketing & Customers
            </h2>
            <x-filament-widgets::widgets
                :columns="$this->getColumns()"
                :data="$this->getWidgetData()"
                :widgets="$this->getMarketingWidgets()"
            />
        </div>

        <!-- Content Group -->
        <div class="space-y-4">
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                Content & Blog
            </h2>
            <x-filament-widgets::widgets
                :columns="$this->getColumns()"
                :data="$this->getWidgetData()"
                :widgets="$this->getContentWidgets()"
            />
        </div>
    </div>
</x-filament-panels::page>
