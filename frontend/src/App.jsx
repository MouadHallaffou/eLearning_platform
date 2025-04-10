import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import CategoriesPage from "./pages/CategoriesPage";
import Header from "./components/common/Header";
import Footer from "./components/common/Footer";
import CourseList from "./components/courses/CourseList";
import CourseForm from "./components/courses/CourseForm";

const Layout = ({ children }) => {
  return (
    <div className="flex flex-col min-h-screen">
      <Header />
      <main className="flex-grow container mx-auto px-4 py-6">
        {children}
      </main>
      <Footer />
    </div>
  );
};

function App() {
  return (
    <Router>
      <Routes>
        <Route 
          path="/" 
          element={
            <Layout>
              <h1>bienvenue dans lla platforme e-learning 😎</h1>
            </Layout>
          } 
        />
        
        <Route 
          path="/categories" 
          element={
            <Layout>
              <CategoriesPage />
            </Layout>
          } 
        />
        
        <Route 
          path="/courses" 
          element={
            <Layout>
              <CourseList/>
            </Layout>
          } 
        />

        <Route 
          path="/courses/new" 
          element={
            <Layout>
              <CourseForm />
            </Layout>
          } 
        />
      </Routes>
    </Router>
  );
}

export default App;