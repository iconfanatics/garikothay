<x-filament-panels::page>
    @if (count($this->getHeaderWidgets()))
        <x-filament-widgets::widgets
            :columns="$this->getHeaderWidgetsColumns()"
            :data="$this->getWidgetData()"
            :widgets="$this->getHeaderWidgets()"
        />
    @endif
</x-filament-panels::page>
