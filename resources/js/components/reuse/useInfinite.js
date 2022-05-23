import { useContext } from 'react';
import { AuthContext } from '../../contexts/AuthContext';
import useSWRInfinite from 'swr/infinite';
import axios from 'axios';

const useInfinite = (url, per_page) => {
  const { token } = useContext(AuthContext);
  const fetcher = (url) =>
    axios({
      method: "get",
      url: url,
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + token,
      },
    })
      .then((response) => {
        console.log(response.data.data);
        return response.data.data.data;
      })

  const getKey = (pageIndex, previousPageData) => {
    pageIndex = pageIndex + 1;
    if (previousPageData && !previousPageData.length) return null
    return `${window.location.origin}/${url}?page=${pageIndex}`
  }

  const { data, size: page, setSize: setPage, error: erorFromInfinite } = useSWRInfinite(getKey, fetcher);

  const paginatedData = data?.flat();

  const isReachedEnd = data && data[data.length - 1]?.length < per_page;

  return {
    data,
    page,
    setPage,
    erorFromInfinite,
    paginatedData,
    isReachedEnd
  };
}

export default useInfinite;