export interface Product {
  product_id: number;
  product_code: string;
  product_name: string;
  slug: string;
  display_name: string;
  name_si: string;
  name_ti: string;
  print_name: string;
  section_id: number;
  department_id: number;
  category_id: number;
  brand_id: number;
  measurement: string;
  reorder_level: number;
  lead_days: number;
  cost_price: number;
  selling_price: number;
  minimum_price: number;
  wholesale_price: number;
  price_2: number;
  item_type: string;
  item_location: string;
  image_path: string;
  created_by: string;
  created_at: string;
  active_status: number;
  generic_id: string | null;
  supplier_list: string;
  size_id: number;
  color_id: number | null;
  product_description: string;
  how_to_use: string | null;
  recipe_type: string;
  barcode: string;
  expiry_good: number;
  location_list: string;
  opening_stock: number;
  special_promo: number;
  special_promo_type: string;
  special_promo_message: string | null;
  rating: string;
  review: number;
  long_description: string;
  benefits: string;
  specifications: string;
  category: string;
  meta_description: string | null;
  reviews: string | null;
  hover_image: string | null;
}

export interface ProductCardProps {
  product: Product;
  onAddToCart: (productId: number) => void;
  onToggleWishlist: (productId: number) => void;
  isInWishlist: boolean;
}


export interface ProductViewProps {
  product: Product;
}

export interface CartItems {
  id: string;
  name: string;
  price: number;
  quantity: number;
  image: string;
}

export interface CartItem extends Product {
  quantity: number;
}

export type Theme = 'light' | 'dark';


export interface ProductCategoryViewProps {
  categoryName: string;
  searchTerm?: string;
  initialData?: any;
  toggleBgColor: number
}


export interface Review {
  id: number;
  user: string;
  rating: number;
  date: string;
  title: string;
  comment: string;
  verified: boolean;
  helpful: number;
}


export interface TrendingProductCardProps {
  product: Product;
  onToggleWishlist?: (productId: number) => void;
  isInWishlist?: boolean;
  salesCount?: number;
}



export interface TrendingProductCardProps {
  product: Product;
  onToggleWishlist?: (productId: number) => void;
  isInWishlist?: boolean;
  salesCount?: number;
}