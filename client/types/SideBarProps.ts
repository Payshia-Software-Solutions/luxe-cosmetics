import { Filters } from "@/types/Filters";

export interface SideBarProps {
    onFilterChange: (filterType: keyof Filters | "resetAll", value: string[] | boolean | [number, number] | string) => void;
    activeFilters: Filters;
}