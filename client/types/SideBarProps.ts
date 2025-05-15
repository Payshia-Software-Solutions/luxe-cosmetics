import { FilterState } from "@/types/FilterState";
export interface SideBarProps {
    onFilterChange: (filterType: string, value: any) => void;
    activeFilters: FilterState;
}