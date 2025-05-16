export interface SectionHeaderProps {
    title: string;
    isExpanded: boolean;
    count?: number;
    onToggle?: () => void;
}
