import React, { useEffect } from "react";
import { category } from "../api/api";
import { useState } from "react";

const CategoryList = () => {
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    try {
      const fetchCategroData = async () => {
        const response = await category.getCategories();
        console.log(response.data);
        setCategories(response.data.data);
      };
      fetchCategroData();
    } catch (error) {
      console.log(error);
    }
  }, []);

  return (
    <ol>
      {categories.map((ctg, index) => (
        <li key={index}>
          <h1>{ctg.name}</h1>
          <ul>
            {ctg.subcategories.map((sub, subIndex) => (
              <li className="text-gray-600 pl-3" key={subIndex}>
                <h3>{sub.name}</h3>
              </li>
            ))}
          </ul>
        </li>
      ))}
    </ol>
  );
  
};

export default CategoryList;
