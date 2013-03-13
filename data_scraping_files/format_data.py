import simplejson as json


def main():
    f = open('out.txt')
    data = json.loads(f.readline())
    f.close()
    queries = []
    counter = 0
    shit_data = {}
    shit_count = 0
    for x in range(0, len(data)-1):
        
        state = ""
        city = ""
        uni = data[str(x)]
        fa = uni['formatted_address']
        countr_start = fa.rfind(',')
        country = fa[countr_start+2:]

        if not (country == 'USA' or country == "United States"):
            continue


        if len(uni['postal']) == 5:
            # Good data
            state = uni['state']['short_name']
            city = uni['city']['long_name']
        else:
            tmp = fa.rfind(", ", 0, countr_start)
            state_zip = fa[tmp+2:countr_start]
            s = state_zip.split(" ")
            state = s[0]
            # if(state == 'exas'):
            #     shit_data[shit_count] = uni
            #     shit_count+=1
            #     continue

            city_tmp = fa.rfind(',', 0, tmp)
            city = fa[city_tmp+1:tmp].strip()
            # if city == 'US':
            #     shit_data[shit_count] = uni
            #     shit_count+=1
            #     continue

        query = "INSERT into universities (name, city, state, domain) VALUES ('{0}', '{1}', '{2}', '{3}');".format(uni['name'], city, state, uni['domain'])
        # print query
        print query
            # print state + " " + uni['name']



    #     else:
    #         if len(uni['postal']) == 4:
    #             not_so_shitty[counter2] = uni
    #             counter2 += 1
    #         else:
    #             super_shit_data[counter3] = uni
    #             print uni['name']
    #             counter3 += 1

    
    # print "good data:" + str(len(new_data))
    # print "somewhat_shit data:" + str(len(not_so_shitty))
    # print "shit utter shit data:" + str(len(super_shit_data));

    # f = open('good_data.txt', 'w')
    # f.write(json.dumps(new_data))
    # f.close()

    # f = open('shit_data.txt', 'w')
    # f.write(json.dumps(not_so_shitty))
    # f.close()

    # f = open('super_shit_data.txt', 'w')
    # f.write(json.dumps(super_shit_data))
    # f.close()

        # uni = data[str(x)]['formatted_address']
        # merica_start = uni.rfind(',')
        # country = uni[merica_start+2:]
        # tmp = uni.rfind(", ", 0, merica_start)
        # state_zip = uni[tmp+2:merica_start]
        # try:
        #     state, z = state_zip.split(" ")
        # except:
        #     pass
        # print state + " " + z
    # for d in data:
    #     print d
    # print a
    # r = requests.get('http://maps.googleapis.com/maps/api/geocode/json?address='+ schools[0]['name'].replace(" ", "+") +'&sensor=false')
    # json_response = json.loads(r.)



if __name__ == "__main__":
    main()
