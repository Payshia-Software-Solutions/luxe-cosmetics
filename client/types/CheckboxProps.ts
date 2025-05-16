export interface CheckboxProps {
    id: string;
    label: React.ReactNode;
    checked?: boolean;
    onChange?: () => void;
}