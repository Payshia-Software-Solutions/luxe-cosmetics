export interface PriceFilterProps {
    min: number;
    max: number;
    value: [number, number];
    onChange?: (value: [number, number]) => void;
}
