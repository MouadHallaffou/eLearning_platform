import axios  from "axios";

const api = axios.create({
    baseURL:"http://localhost:8000/api",
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
})

export default api


export const category = {
    getCategories: () => api.get("/categories"),
    getCategory: (id) => api.get(`/categories/${id}`),
    createCategory: (data) => api.post("/categories", data),
    updateCategory: (id, data) => api.put(`/categories/${id}`, data),
    deleteCategory: (id) => api.delete(`/categories/${id}`),
}

export const course = {
    getCourses: () => api.get("/courses"),
    getCourse: (id) => api.get(`/courses/${id}`),
    createCourse: (data) => api.post("/courses", data),
    updateCourse: (id, data) => api.put(`/courses/${id}`, data),
    deleteCourse: (id) => api.delete(`/courses/${id}`),
}