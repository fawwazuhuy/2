import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../routes/AuthContext";

const Dashboard = () => {
  const { user, fetchWithAuth } = useAuth();
  const [data, setData] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchData = async () => {
      try {
        const result = await fetchWithAuth("/api/protected-data");
        setData(result);
      } catch (error) {
        console.error("Failed to fetch data:", error);
      }
    };
    fetchData();
  }, []);

  return (
    <div className="p-6">
      <button
        onClick={() => navigate("/logout")}
        className="text-blue-600 hover:underline"
      >
        Logout
      </button>
      <h1 className="text-2xl font-bold">Welcome, {user?.name}</h1>
      {data && (
        <div className="mt-4 p-4 bg-gray-50 rounded">
          <pre>{JSON.stringify(data, null, 2)}</pre>
        </div>
      )}
    </div>
  );
};
export default Dashboard;
