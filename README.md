## Discount calculator

I have implemented a chain of responsibility for calculating the discounts.
Each discount type (customer revenue, free product by category, more items from same category) is a class from the chain.
Besides that each discount can be configured: 
- customer revenue: you can set the threshold and the discount percentage
- free product by category: you can set the category and the quantity 
- more items from same category: you can set the category and the discount percentage

Besides this we have the following flags:
- `is_active` that indicates if the discount is active or not
- `is_stackable` that indicates if we can use this discount in conjunction with other discounts

## Things missing from project
- DDD
- Caching layer, all requests hit database
- No exception handling and reporting
