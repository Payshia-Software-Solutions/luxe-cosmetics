import  {Review} from  "@/types/Review";

export interface Product {
  id: string;
  slug: string;
  name: string;
  price: number;
  rating: number;
  review: number;
  description: string;
  longDescription: string;
  benefits: string[];
  specifications: Record<string, string>;
  ingredients: string;
  images: string[];
  category: string;
  breadcrumbs: string[];
  metaDescription: string;
  reviews: Review[];
}


export const products: Product[] = [
  {
    id: '1',
    slug: 'natural-glow-serum',
    name: 'Natural Glow Serum',
    price: 49.99,
    rating: 4.8,
    review: 128,
    description: 'Our bestselling Natural Glow Serum is formulated with powerful antioxidants and natural ingredients to give your skin a radiant, healthy glow. This lightweight serum absorbs quickly and works for all skin types.',
    longDescription: `
      Transform your skincare routine with our Natural Glow Serum, a revolutionary formula designed to enhance your skin's natural radiance while providing deep nourishment and protection.

      This lightweight yet powerful serum combines the latest in skincare technology with natural ingredients to deliver visible results. The fast-absorbing formula penetrates deeply into the skin, working at a cellular level to improve texture, tone, and overall skin health.

      Perfect for all skin types, this serum can be used both morning and night as part of your daily skincare routine. Its non-comedogenic formula ensures it won't clog pores while delivering essential nutrients to your skin.
    `,
    benefits: [
      'Brightens and evens skin tone',
      'Reduces fine lines and wrinkles',
      'Hydrates and nourishes',
      'Protects against environmental damage'
    ],
    specifications: {
      'Skin Type': 'All Skin Types',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '24 months',
      'Country of Origin': 'USA'
    },
    ingredients: 'Hyaluronic Acid, Vitamin C, Niacinamide, Peptides, Natural Extracts',
    images: [
      'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
      'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
      'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Transform your skin with our Natural Glow Serum. This lightweight, fast-absorbing formula combines powerful antioxidants and natural ingredients for a radiant, healthy complexion.',
    reviews: [
      {
        id: 1,
        user: 'Sarah M.',
        rating: 5,
        date: '2024-02-15',
        title: 'Amazing Results!',
        comment: 'I\'ve been using this serum for a month and my skin has never looked better. The glow is real!',
        verified: true,
        helpful: 45
      },
      {
        id: 2,
        user: 'Emily R.',
        rating: 4,
        date: '2024-02-10',
        title: 'Good but pricey',
        comment: 'The serum works well and I can see improvement in my skin texture. A bit expensive though.',
        verified: true,
        helpful: 32
      }
    ]
  },
  // New products under Serums category
  {
    id: '2',
    slug: 'vitamin-c-serum',
    name: 'Vitamin C Serum',
    price: 39.99,
    rating: 4.5,
    review: 105,
    description: 'A brightening Vitamin C serum that helps reduce the appearance of dark spots and promotes an even skin tone.',
    longDescription: `
      Infused with stabilized Vitamin C, this serum helps brighten the skin and even out the complexion. It also provides antioxidant protection, reducing the effects of free radicals on the skin.
      
      Perfect for daily use, this serum works best when applied in the morning for a glowing, radiant complexion. It also helps to improve skin elasticity and firmness over time.
    `,
    benefits: [
      'Brightens dark spots',
      'Evens out skin tone',
      'Improves skin elasticity',
      'Protects against environmental damage'
    ],
    specifications: {
      'Skin Type': 'All Skin Types',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '18 months',
      'Country of Origin': 'USA'
    },
    ingredients: 'Vitamin C, Hyaluronic Acid, Aloe Vera, Green Tea Extract',
    images: [
      'https://cosmetics.lk/cdn/shop/files/Cerave_Skin_Renewing_Vitamin_C_Serum_30ml_-_CE005-1primaryimage.webp?v=1742802178',
      'https://images.unsplash.com/photo-1571161159271-e59163de22c3?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8OXx8c2VydW18ZW58MHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Brighten your skin and even out your complexion with our Vitamin C Serum. Packed with powerful antioxidants and soothing ingredients.',
    reviews: [
      {
        id: 1,
        user: 'John D.',
        rating: 5,
        date: '2024-03-01',
        title: 'Skin feels amazing!',
        comment: 'This serum has worked wonders on my skin, and I see fewer dark spots now!',
        verified: true,
        helpful: 22
      },
      {
        id: 2,
        user: 'Laura P.',
        rating: 4,
        date: '2024-03-03',
        title: 'Great results, but slightly sticky',
        comment: 'It works well, but I find it a little sticky at first.',
        verified: true,
        helpful: 18
      }
    ]
  },
  {
    id: '3',
    slug: 'hydrating-hyaluronic-serum',
    name: 'Hydrating Hyaluronic Serum',
    price: 44.99,
    rating: 4.7,
    review: 215,
    description: 'This intensely hydrating serum replenishes moisture levels and helps plump and smooth the skin.',
    longDescription: `
      Give your skin a moisture boost with this potent hyaluronic acid serum. It replenishes lost hydration, leaving the skin soft, smooth, and plump.
      
      This lightweight serum is perfect for dry and dehydrated skin, offering long-lasting hydration without feeling greasy. It can be used daily and is especially effective after cleansing or exfoliating your skin.
    `,
    benefits: [
      'Deeply hydrates skin',
      'Smooths and plumps the skin',
      'Reduces the appearance of fine lines',
      'Improves skin elasticity'
    ],
    specifications: {
      'Skin Type': 'Dry and Dehydrated Skin',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '18 months',
      'Country of Origin': 'USA'
    },
    ingredients: 'Hyaluronic Acid, Aloe Vera, Vitamin E, Glycerin',
    images: [
      'https://cosmetics.lk/cdn/shop/files/Cerave_Hydrating_Hyaluronic_Acid_Serum_30ml_-_CEH01-2_primary.webp?v=1742538355',
      'https://images.unsplash.com/photo-1577755005327-cc77c3c9b8ff?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8Mnx8c2VydW18ZW58MHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Hydrate your skin deeply with our Hyaluronic Serum. This serum replenishes moisture levels, leaving the skin smooth and plump.',
    reviews: [
      {
        id: 1,
        user: 'David F.',
        rating: 5,
        date: '2024-01-15',
        title: 'Hydration at its best!',
        comment: 'This serum leaves my skin so soft and hydrated. Highly recommend it!',
        verified: true,
        helpful: 75
      },
      {
        id: 2,
        user: 'Sophie W.',
        rating: 4,
        date: '2024-01-20',
        title: 'Very hydrating',
        comment: 'It works great, but I wish it absorbed a little faster.',
        verified: true,
        helpful: 53
      }
    ]
  },

  // CeraVe Product
  {
    id: '4',
    slug: 'cerave-hydrating-serum',
    name: 'CeraVe Hydrating Serum',
    price: 29.99,
    rating: 4.6,
    review: 90,
    description: 'CeraVe Hydrating Serum is designed to restore the skin\'s natural moisture balance with hyaluronic acid and ceramides.',
    longDescription: `
      This hydrating serum from CeraVe replenishes moisture and provides essential hydration with a blend of hyaluronic acid and ceramides. It is fragrance-free, non-comedogenic, and ideal for daily use.
      
      Perfect for dry to normal skin, this serum helps maintain the skin's natural protective barrier while offering long-lasting hydration.
    `,
    benefits: [
      'Restores moisture balance',
      'Helps repair skin barrier',
      'Non-comedogenic',
      'Fragrance-free'
    ],
    specifications: {
      'Skin Type': 'Dry to Normal Skin',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '12 months',
      'Country of Origin': 'USA'
    },
    ingredients: 'Hyaluronic Acid, Ceramides, Glycerin',
    images: [
      'https://cosmetics.lk/cdn/shop/files/Cerave_Hydrating_Hyaluronic_Acid_Serum_30ml_-_CEH01-1primary.webp?v=1742538582',
      'https://images.unsplash.com/photo-1574010218484-c2c2bc52490b?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8Mnx8Y2VyYXZlfGVuY3J5cHRpb258MHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Hydrate your skin with CeraVe Hydrating Serum. Formulated with hyaluronic acid and ceramides to restore moisture.',
    reviews: [
      {
        id: 1,
        user: 'Sophia T.',
        rating: 5,
        date: '2024-03-15',
        title: 'Great Hydration!',
        comment: 'I love how my skin feels so hydrated and smooth after using this serum.',
        verified: true,
        helpful: 40
      },
      {
        id: 2,
        user: 'Mark S.',
        rating: 4,
        date: '2024-03-17',
        title: 'Good, but a bit thin',
        comment: 'It works well, but the texture is thinner than other serums I\'ve used.',
        verified: true,
        helpful: 18
      }
    ]
  },

  // L'Oréal Product
  {
    id: '5',
    slug: 'loreal-revitalift-serum',
    name: 'L\'Oréal Revitalift Serum',
    price: 39.99,
    rating: 4.7,
    review: 205,
    description: 'L\'Oréal Revitalift Serum helps reduce wrinkles and improve skin texture with its anti-aging formula.',
    longDescription: `
      This anti-aging serum from L'Oréal works to reduce the appearance of wrinkles and improve skin texture. Its formula, enriched with pro-retinol and vitamin C, helps to revitalize and brighten the skin.
      
      Suitable for all skin types, it is ideal for use in the morning and evening for smoother, younger-looking skin.
    `,
    benefits: [
      'Reduces the appearance of wrinkles',
      'Improves skin texture',
      'Brightens and revitalizes skin',
      'Contains Pro-Retinol and Vitamin C'
    ],
    specifications: {
      'Skin Type': 'All Skin Types',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '12 months',
      'Country of Origin': 'France'
    },
    ingredients: 'Pro-Retinol, Vitamin C, Glycerin, Water',
    images: [
      'https://www.essentials.lk/cdn/shop/files/L_OrealParisRevitalift1.5_PureHyaluronicAcidFaceSerum30ml.jpg?crop=center&height=600&v=1687523696&width=600',
      'https://images.unsplash.com/photo-1571264694207-d0d43fe41c9d?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8Mnx8bG9yZWFsX3Jldml0YWxpZnR8ZW58MHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Reduce wrinkles and improve texture with L\'Oréal Revitalift Serum, enriched with Pro-Retinol and Vitamin C.',
    reviews: [
      {
        id: 1,
        user: 'Isabella H.',
        rating: 5,
        date: '2024-03-20',
        title: 'I see a difference!',
        comment: 'My wrinkles are visibly reduced, and my skin looks smoother!',
        verified: true,
        helpful: 67
      },
      {
        id: 2,
        user: 'Lucas P.',
        rating: 4,
        date: '2024-03-22',
        title: 'Effective serum',
        comment: 'It works well, but I wish the scent was a bit milder.',
        verified: true,
        helpful: 52
      }
    ]
  },

  // Garnier Product
  {
    id: '6',
    slug: 'garnier-skin-active-serum',
    name: 'Garnier SkinActive Serum',
    price: 29.99,
    rating: 4.4,
    review: 120,
    description: 'Garnier SkinActive Serum hydrates and revitalizes skin with natural ingredients like vitamin C and aloe vera.',
    longDescription: `
      This hydrating serum from Garnier is infused with natural ingredients like Vitamin C and Aloe Vera to provide intense hydration and brighten the skin.
      
      Perfect for daily use, this serum works to improve skin texture and give your skin a fresh, glowing appearance.
    `,
    benefits: [
      'Hydrates and revitalizes skin',
      'Brightens and evens skin tone',
      'Contains Vitamin C and Aloe Vera',
      'Suitable for all skin types'
    ],
    specifications: {
      'Skin Type': 'All Skin Types',
      'Size': '30ml / 1.0 fl oz',
      'Usage': 'Morning and Evening',
      'Storage': 'Store in a cool, dry place',
      'Shelf Life': '12 months',
      'Country of Origin': 'France'
    },
    ingredients: 'Vitamin C, Aloe Vera, Glycerin',
    images: [
      'https://cosmetics.lk/cdn/shop/files/Garnier_SkinActive_Vitamin_C_Serum_30ml_-_GSA07-1_Primary.webp?v=1742538883',
      'https://images.unsplash.com/photo-1589231585575-62a9c5be158b?crop=entropy&cs=tinysrgb&fit=max&ixid=MnwzNjUyOXwwfDF8c2VhY2h8Mnx8Z2Fybmllcl9za2luY2FjdGl2ZXxlbnwyfHx8fHwxNjg4NjQ5Mjg5&ixlib=rb-1.2.1&q=80&w=1080'
    ],
    category: 'Serums',
    breadcrumbs: ['Skincare', 'Treatments', 'Serums'],
    metaDescription: 'Hydrate and brighten your skin with Garnier SkinActive Serum, packed with Vitamin C and Aloe Vera.',
    reviews: [
      {
        id: 1,
        user: 'Lily J.',
        rating: 5,
        date: '2024-02-20',
        title: 'Perfect for my skin!',
        comment: 'This serum has helped with my skin’s radiance and hydration.',
        verified: true,
        helpful: 38
      },
      {
        id: 2,
        user: 'Oliver B.',
        rating: 4,
        date: '2024-02-22',
        title: 'Good results',
        comment: 'It worked well, but I feel like I need to use it more often to see better results.',
        verified: true,
        helpful: 24
      }
    ]
  }


  
];

