from sys import argv
import os
import urllib.request
from bs4 import BeautifulSoup
import json
import socket
import time
import random
import re


def returnError(code, uri=None, mess=None):
    page = {
        'Url': uri,
        'Code': code,
        'Message': mess,
    }
    jsonData = json.dumps(page)

    print(jsonData)
    exit()


def req(uri):
    # Пауза парсера
    time.sleep(random.randrange(91, 1127) / 1000)
    try:
        req = urllib.request.Request(uri, headers={'User-Agent': 'Mozilla/5.0'})
        webpage = urllib.request.urlopen(req)
    except urllib.error.HTTPError as e:
        mess = str(e)
        returnError(e.code, uri, mess)
        return False
    except socket.error:
        returnError(0, uri, 'proxy')
    except Exception as detail:
        returnError(0, uri, type(detail).__name__)
    else:
        return webpage


def parseLinks(uri, domain):
    link = uri
    links = []
    soup = req(link)
    page = BeautifulSoup(soup.read(), 'lxml')
    list = page.find_all('a', class_='letter_nav_s')
    for a in list:
        links.append({'link': domain + '/' + a.get('href')})
    jsonData = json.dumps(links)
    print(jsonData)


def parseBook(uri, domain):
    link = uri
    soup = req(link)
    page = BeautifulSoup(soup.read(), 'lxml')
    td_center_color = page.find_all('tr', class_='td_center_color')
    # text = td_center_color[0].find('p').text
    text = td_center_color[0].find('p').get_text(strip=True, separator='||||').split('||||')
    book = {}
    book['search'] = {}
    book['database'] = {}
    book['params'] = {}
    authors = []
    title = ''
    series = ''
    year = ''
    publishers = []

    i = 0
    while i < len(text):
        book['params'][text[i]] = text[i + 1]
        if text[i] == 'Серия:':
            series += text[i + 1]
        elif text[i] == 'Автор:':
            author = text[i+1].split(',')

            j = 0
            while j < len(author):
                authors.append(author[j])
                j += 1
        elif text[i] == 'Название:':
            title += text[i + 1]
        elif text[i] == 'Издательство:':
            publisher = text[i + 1].split(',')
            j = 0
            while j < len(publisher):
                publishers.append(publisher[j])
                j += 1
        elif text[i] == 'Год:':
            year += text[i + 1]
        i += 2

    book['search']['authors'] = authors
    book['search']['publishers'] = publishers
    book['search']['year'] = year
    book['search']['series'] = series

    book['database']['text'] = re.sub(r'\s+', ' ', td_center_color[1].find('p', class_='span_str').text)
    book['database']['title'] = title
    book['pages'] = parsePage(domain+'/read_book.php?'+uri.split('?')[1]+'&p=1', 'link', domain)
    book['image'] = {'link': domain+'/'+td_center_color[0].find('img').get('src')}

    jsonData = json.dumps(book)
    print(jsonData)


def parsePage(uri, type, domain):

        soup = req(uri)
        page = BeautifulSoup(soup.read(), 'lxml')
        error = page.find('em')
        if error is not None and error.text == 'Эта книга удалена по требованию правообладателя. Прочитать её на нашем сайте нельзя.':
            return 0

        div = page.find_all('div', attrs={'style': 'text-align: left; font-size: 0.8em; margin-bottom: 10px;'})
        if len(div) > 0:
            div[0].decompose()
        div = page.find_all('div', attrs={'style': 'text-align: right; font-size: 0.8em; margin-top: 10px;'})
        if len(div) > 0:
            div[0].decompose()
        content = page.find('div', class_='MsoNormal')

        for iframe in content.find_all('iframe'):
            iframe.decompose()

        if type == 'link':
            nav = page.find('div', class_='navigation').find_all('a')
            urls = []
            if len(nav) > 0:
                pages = nav[len(nav) - 2].text
                url = uri[:-1]
                i = 1
                while i <= int(pages):
                    urls.append({'link': url+str(i)})
                    i+=1
            else:
                urls.append({'link': uri})
            return urls
        else:
            for img in content.find_all('img'):
                if 'src' in img:
                    img['src'] = '/' + img['src']

            page_content = {}
            page_content['content'] = content.prettify()
            page_content['imgs'] = []
            imgs = content.find_all('img')
            i = 0
            while i < len(imgs):
                if imgs[i].get('src') != None:
                    page_content['imgs'].append({'link': domain+'/'+imgs[i].get('src')})
                i += 1
            # print(content.encode('utf-8'))

            jsonData = json.dumps(page_content)
            print(jsonData)

        # f = open('1.html', 'w', encoding='utf-8')
        # f.write(str(content))
        # f.close()
        # i = 2
        # while i <= int(pages):

            # soup = req(url+str(i))
            # page = BeautifulSoup(soup.read(), 'lxml')
            # page.find_all('div', attrs={'style': 'text-align: left; font-size: 0.8em; margin-bottom: 10px;'})[
            #     0].decompose()
            # page.find_all('div', attrs={'style': 'text-align: right; font-size: 0.8em; margin-top: 10px;'})[
            #     0].decompose()
            # content = page.find('div', class_='MsoNormal').prettify()
            # print(content)
            # f = open(str(i)+'.html', 'w', encoding='utf-8')
            # f.write(str(content))
            # f.close()
            # i+=1



def parseImage(uri):
    image = req(uri).read()
    path = uri.split('/')
#     img_dir = 'public/'
    img_dir = '/mnt/volume_fra1_01/'
    i = 3
    while i < len(path)-1:
        img_dir += path[i] + '/'
        i += 1
    if not os.path.isdir(img_dir):
        os.makedirs(img_dir)
    f = open(img_dir + path[len(path)-1], "wb")
    f.write(image)
    f.close()
    jsonData = json.dumps('success')
    print(jsonData)


def parse(argv):
    # Точка входа
    try:
        script, url, proxy, type = argv
    except ValueError:
        returnError(code=0, mess='Parameter is null')

    proxy_host = proxy
    uri = url

    domain_str = list(filter(None, url.split('/')))
    domain = domain_str[0] + '//' + domain_str[1]
    # # Подключение прокси
    # socket.setdefaulttimeout(60)
    # proxy_support = urllib.request.ProxyHandler({
    #     'http': proxy_host,
    #     'https': proxy_host,
    # })
    # opener = urllib.request.build_opener(proxy_support)
    # urllib.request.install_opener(opener)

    if type == 'links':
        parseLinks(uri, domain)
    elif type == 'book':
        parseBook(uri, domain)
    elif type == 'page':
        parsePage(uri, type, domain)
    elif type == 'image':
        parseImage(uri)

    # parsePage(uri, proxy)
    # parseList()
    # jsonData = json.dumps(result)
    # print(jsonData)
    exit()


parse(argv)
